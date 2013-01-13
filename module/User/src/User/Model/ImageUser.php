<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class ImageUser extends AbstractModel
{
    protected $itemClass = 'User\Item\ImageUser';

    public function changeImage(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('changeimage.pre');

        $item->save();

        $this->trigger('changeimage');

        $this->trigger('changeimage.post');

        return $item->id;
    }

}
