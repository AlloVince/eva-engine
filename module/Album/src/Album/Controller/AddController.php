<?php

namespace Album\Controller;

use Zend\Mvc\Controller\ActionController,
    Album\Model\AlbumTable,
    Album\Model\Album,
    Album\Form\AlbumForm,
    Zend\View\Model\ViewModel;

class AddController extends ActionController
{
    /**
     * @var \Album\Model\AlbumTable
     */
    protected $albumTable;

    public function indexAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setAttribute('label', 'Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $album = new Album();
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->post());
            if ($form->isValid()) {

                $album->populate($form->getData());
                $this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');

            }
        }

        return array('form' => $form);
    }

    public function setAlbumTable(AlbumTable $albumTable)
    {
        $this->albumTable = $albumTable;
        return $this;
    }

    public function getAlbumTable()
    {
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('album-table');
        }
        return $this->albumTable;
    }
}
