<?php
namespace Epic\Form;

class AlbumCreateForm extends \Album\Form\AlbumForm
{
    protected $subFormGroups = array(
        'default' => array(
        ),
    );

    protected $mergeElements = array(
    );

    protected $mergeFilters = array(
        'title' => array(
            'required' => true,
        ),
        'urlName' => array (
            'required' => false,
            'validators' => array (
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'options' => array(
                        'field' => 'urlName',
                        'table' => 'album_albums',
                    ),
                ),
            ),
        ),
    );

    public function beforeBind($data)
    {
        $config = \Eva\Api::_()->getModuleConfig('Epic');
        $visibility = $config['album']['visibility']['default'];

        $data['visibility'] = $visibility; 

        return $data;
    }

    public function prepareData($data)
    {
        if(isset($data['EventFile'])){
            $data['EventFile']['event_id'] = $data['id'];
        }
        
        return $data;
    }
}
