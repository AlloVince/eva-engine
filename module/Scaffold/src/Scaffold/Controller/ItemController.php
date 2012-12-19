<?php
namespace Scaffold\Controller;

use Eva\Mvc\Controller\RestfulModuleController,
    Eva\Api,
    Eva\Db\Metadata\Metadata,
    Eva\View\Model\ViewModel;

class ItemController extends RestfulModuleController
{
    protected $addResources = array(
        'array',
        'html'
    );
    
    protected $renders = array(
        'restPutFormHtml' => 'form/put',    
    ); 

    public function restGetFormHtml()
    {
    }

    public function restGetItem() 
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
    
    public function restPutItemArray()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $tableName = $this->params()->fromRoute('id'); 
        
        if(!$postData['selectedColumns']){
            return false;
        }

        return array(
            'columns' => $postData['selectedColumns'],
        );
    }
    
    public function restPutFormHtml()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
            
        $mainForm = $postData->form;

        $form = Api::_()->getForm($mainForm);
        $elements = $form->getMergedElements();

        $subFormString = $postData->subform;

        $subFormElements = array();

        if ($subFormString) {
            $subForms = explode(',', $subFormString);
            foreach ($subForms as $subForm) {
                $form = Api::_()->getForm($subForm);
                $subFormElements[$subForm] = $form->getMergedElements();
            }
        }
        
        $generator = new \Scaffold\Model\FormHtmlGenerator();
        $generator->setElements($elements);
        $subFormElements ? $generator->setSubFormElements($subFormElements) : null;
        $generator->setFormClassName($mainForm);
        list($elements, $subForms) = $generator->convertToFormHtml();

        $subFormsCode = $generator->printCode($subForms);
        $formClassName = $generator->getFormClassName();
   
        return array(
            'formClassName' => $formClassName,
            'elements' => $elements,
            'subFormsCode' => $subFormsCode,
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

        $mergeElements = array();
        
        $mergeFilters = array();
            
        foreach ($columns as $column) {
            $mergeElements[$column['name']] = array(
                'name' => $column['name'],
            );  

            $mergeFilters[$column['name']] = array(
                'name' => $column['name'],
                'required' => $column['required'] ? true : false,
                'filters' => array(
                ),
                'validators' => array(
                ),
            );   

            if ($column['data_type'] != 'enum') {
                $mergeElements[$column['name']]['attributes'] = array(
                    'type' => $column['data_type'] == 'longtext' ? 'textarea' : 'text',
                    'label' => ucfirst($column['name']),
                );

                if ($column['isHidden'] == true) {
                    $mergeElements[$column['name']]['attributes']['type'] = 'hidden'; 
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

                $mergeElements[$column['name']]['attributes'] = array(
                    'type' => $column['inputType'] == 'raido' ? 'raido' : 'select',
                    'label' => ucfirst($column['name']),
                    'options' => $selectOptions,
                ); 

                if ($column['column_default']) {
                    $mergeElements[$column['name']]['attributes']['value'] = $column['column_default']; 
                }    
            }
        }

        $mergeElementsString = var_export($mergeElements, true);
        $mergeFiltersString = var_export($mergeFilters, true);

        $formString = 
            "<?php
namespace $moduleName\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class {$tabName}Form extends Form
{
    ".'   protected $mergeElements = ' . $mergeElementsString . ';

    protected $mergeFilters = ' . $mergeFiltersString . ';

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

    public function p($d)
    {
        echo "<pre>";
        print_r($d);
        echo "</pre>";
    }
}
