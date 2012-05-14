<?php

namespace Album\Controller;

use Eva\Mvc\Controller\ActionController,
    Album\Model\AlbumTable,
    Album\Form\AlbumForm,
    Eva\View\Model\ViewModel;

class AlbumController extends ActionController
{
    /**
     * @var \Album\Model\AlbumTable
     */
    protected $albumTable;

    public function indexAction()
    {
        $model = new ViewModel(array(
            'albums' => $this->albumTable->fetchAll(),
		));
		return $model;
    }

    public function addAction()
    {
        $form = new AlbumForm();
        $form->submit->setLabel('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $formData = $request->post()->toArray();
            if ($form->isValid($formData)) {
                $artist = $form->getValue('artist');
                $title  = $form->getValue('title');
                $this->albumTable->addAlbum($artist, $title);

                // Redirect to list of albums
                return $this->redirect()->toRoute('default', array(
                    'controller' => 'album',
                    'action'     => 'index',
                ));

            }
        }

        return array('form' => $form);

    }

    public function editAction()
    {
        $form = new AlbumForm();
        $form->submit->setLabel('Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $formData = $request->post()->toArray();
            if ($form->isValid($formData)) {
                $id     = $form->getValue('id');
                $artist = $form->getValue('artist');
                $title  = $form->getValue('title');
                
                if ($this->albumTable->getAlbum($id)) {
                    $this->albumTable->updateAlbum($id, $artist, $title);
                }

                // Redirect to list of albums
                return $this->redirect()->toRoute('default', array(
                    'controller' => 'album',
                    'action'     => 'index' ,
                ));
            }
        } else {
            $id = $request->query()->get('id', 0);
            if ($id > 0) {
                $album = $this->albumTable->getAlbum($id);
                if ($album) {
                    $form->populate($album->getArrayCopy());
                }
            }
        }

        return array('form' => $form);
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->post()->get('del', 'No');
            if ($del == 'Yes') {
                $id = $request->post()->get('id');
                $this->albumTable->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('default', array(
                'controller' => 'album',
                'action'     => 'index',
            ));
        }

        $id = $request->query()->get('id', 0);
        return array('album' => $this->albumTable->getAlbum($id));        
    }

    public function setAlbumTable(AlbumTable $albumTable)
    {
        $this->albumTable = $albumTable;
        return $this;
    }    
}
