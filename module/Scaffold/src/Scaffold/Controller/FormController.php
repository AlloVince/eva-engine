<?php
namespace Scaffold\Controller;

use Eva\Mvc\Controller\RestfulModuleController,
    Eva\Api,
    Eva\Db\Metadata\Metadata,
    Eva\View\Model\ViewModel;

class FormController extends RestfulModuleController
{
    protected $addResources = array(
        'html'
    );
    
    protected $renders = array(
        'restPutFormHtml' => 'form/put',    
    ); 

    public function restIndexForm()
    {
        $adapter = Api::_()->getDbAdapter();
        
        $metadata = new Metadata($adapter);

        $tables = $metadata->getTableNames();

        return array(
            'tables' => $tables,
        );
    }

    public function restGetFormHtml()
    {
    }

    public function restGetForm() 
    {
        $query = $this->getRequest()->getQuery();
        
        $tab = $this->getEvent()->getRouteMatch()->getParam('id'); 
        
        $adapter = Api::_()->getDbAdapter();
        
        $metadata = new Metadata($adapter);

        $columns = $metadata->getColumns($tab);
        
        $props = array(
            'name', 'ordinal_position', 'column_default', 'is_nullable',
            'data_type', 'character_maximum_length', 'character_octet_length',
            'numeric_precision', 'numeric_scale', 'numeric_unsigned',
            'erratas', 'column_type'
        );
        
        foreach ($columns as $column) {
            $columnName = $column->getName();
            foreach ($props as $prop) {
                $res[$columnName][$prop] = $column->{'get' . str_replace('_', '', $prop)}();
            }
        }
        return array(
            'columns' => $res,
            'table' => $tab
        );
    }
    
    public function restPutForm()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $tableName = $this->params()->fromRoute('id'); 
        
        if(!$postData['selectedColumns']){
            return false;
        }

        $elements = array();

        foreach($postData['selectedColumns'] as $columnName){
            $elements[$columnName] = array(
                'name' => $columnName,
                'type' => @$postData['inputType'][$columnName],
                'validators' => @$postData['validators'][$columnName],
                'filters' => @$postData['filters'][$columnName],
                'default' => @$postData['defaults'][$columnName],
            );
        
        }

        $generator = new \Scaffold\Model\FormClassGenerator();
        $generator->setElements($elements);
        $generator->setDbTableName($tableName);
        list($elements, $validators) = $generator->convertToFormArray();

        $elementsCode = $generator->printCode($elements);
        $validatorsCode = $generator->printCode($validators);
        $formClassName = $generator->getFormClassName();
        return array(
            'formClassName' => $formClassName,
            'elementsCode' => $elementsCode,
            'validatorsCode' => $validatorsCode,
        );
    }
    
    public function restPutFormHtml()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
            
        $mainForm = $postData->form;

        $form = Api::_()->getForm($mainForm);
        $elements = $form->getMergedElements();

        $formString = '';

        foreach ($elements as $element) {
            $formString .= $this->makeHtml($element);
        }
       
        $subFormString = $postData->subform;
        
        if ($subFormString) {
            $subForms = explode(',', $subFormString);
            foreach ($subForms as $subForm) {
                $form = Api::_()->getForm($subForm);
                $elements = $form->getMergedElements();

                foreach ($elements as $element) {
                   $formString .= $this->makeHtml($element);
                } 
            }
        }
        
        return array(
            'formString' => $formString,
        );
    }

    public function makeForm($columns, $tabName)
    {
        if (!$columns || !$tabName) {
            return $columns;
        }

        $tabNameArray = explode('_',$tabName);

        $moduleName = ucfirst($tabNameArray[1]);

        $tabName = count($tabNameArray) == 3 ? ucfirst($tabNameArray[2]) : ( ucfirst($tabNameArray[2]) . ucfirst($tabNameArray[3]) );

        $baseElements = array();
        
        $baseFilters = array();
            
        foreach ($columns as $column) {
            $baseElements[$column['name']] = array(
                'name' => $column['name'],
            );  

            $baseFilters[$column['name']] = array(
                'name' => $column['name'],
                'required' => $column['required'] ? true : false,
                'filters' => array(
                ),
                'validators' => array(
                ),
            );   

            if ($column['data_type'] != 'enum') {
                $baseElements[$column['name']]['attributes'] = array(
                    'type' => $column['data_type'] == 'longtext' ? 'textarea' : 'text',
                    'label' => ucfirst($column['name']),
                );

                if ($column['isHidden'] == true) {
                    $baseElements[$column['name']]['attributes']['type'] = 'hidden'; 
                }
            
            } else {

                if ($column['column_type']) {
                    $selectString = str_replace(array('enum','(', ')', '\''),'', $column['column_type']);
                    $selectArray = explode(',' ,$selectString);
                    $selectOptions = array();

                    if ($column['inputType'] == 'raido') {
                        foreach ($selectArray as $select) {
                            $selectOptions[ucfirst($select)] = $select;
                        } 
                    } else {
                        foreach ($selectArray as $select) {
                            $selectOptions[] = array(
                                'label' => ucfirst($select),
                                'value' => $select,
                            );
                        }
                    }
                }            

                $baseElements[$column['name']]['attributes'] = array(
                    'type' => $column['inputType'] == 'raido' ? 'raido' : 'select',
                    'label' => ucfirst($column['name']),
                    'options' => $selectOptions,
                ); 

                if ($column['column_default']) {
                    $baseElements[$column['name']]['attributes']['value'] = $column['column_default']; 
                }    
            }
        }

        $baseElementsString = var_export($baseElements, true);
        $baseFiltersString = var_export($baseFilters, true);

        $formString = 
            "<?php
namespace $moduleName\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class {$tabName}Form extends Form
{
    ".'   protected $baseElements = ' . $baseElementsString . ';

    protected $baseFilters = ' . $baseFiltersString . ';

    }';
        
        $formString = preg_replace('/\d \=>/','',$formString);

        return $formString;
    }
    
    public function makeHtml($element)
    {
        if (!$element) {
            return $element;
        }   
    
        $html = <<<abc
<div class="control-group <?=\$form->isError('{$element['name']}') ? 'error' : '';?>">
    <?=\$form->helper('{$element['name']}', 'label', array('class' => 'control-label'))?>
    <div class="controls">
        <?=\$form->helper('{$element['name']}', array('class' => ''))?>
        <div class="help-block"><?=\$form->helper('{$element['name']}', 'formElementErrors')?></div>
    </div>
</div>
abc;
    
        return $html . "\n";
    }
}
