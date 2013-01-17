<?php
namespace Album\Form;

class AlbumCreateForm extends AlbumForm
{
    protected $subFormGroups = array(
        'default' => array(
            'CategoryAlbum' => array(
                'formClass' => 'Album\Form\CategoryAlbumForm',
                'collection' => true,
                'optionsCallback' => 'initCategories',
            ),
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
        //Data is array is for display
        if(isset($data['CategoryAlbum']) && is_array($data)){
            $categoryAlbums = array();
            $subForms = $this->get('CategoryAlbum');
            foreach($subForms as $key => $subForm){
                $categoryAlbum = array();
                $category = $subForm->getCategory();
                if (!$category) {
                    continue;
                }
                $category = $category->toArray();
                $categoryAlbum['category_id'] = $category['id'];
                foreach($data['CategoryAlbum'] as $categoryAlbumArray){
                    if($categoryAlbum['category_id'] == $categoryAlbumArray['category_id']){
                        $categoryAlbum = array_merge($categoryAlbum, $categoryAlbumArray);
                        break;
                    }
                }
                $categoryAlbums[] = $categoryAlbum;
            }
            $data['CategoryAlbum'] = $categoryAlbums;
        }
        return $data;
    }

    public function prepareData($data)
    {
        if(isset($data['AlbumFile'])){
            $data['AlbumFile']['album_id'] = $data['id'];
        }
        
        $categoryAlbums = array();
        if(isset($data['CategoryAlbum']) && $data['CategoryAlbum']){
            foreach($data['CategoryAlbum'] as $categoryAlbum){
                if(isset($categoryAlbum['category_id']) && $categoryAlbum['category_id']){
                    $categoryAlbums[] = $categoryAlbum;
                }
            }
            $data['CategoryAlbum'] = $categoryAlbums;
        }

        return $data;
    }
}
