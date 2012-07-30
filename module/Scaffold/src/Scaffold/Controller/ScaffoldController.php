<?php
namespace Scaffold\Controller;

use Eva\Mvc\Controller\RestfulModuleController,
    Eva\Api,
    Eva\Db\Metadata\Metadata,
    Eva\View\Model\ViewModel;

class ScaffoldController extends RestfulModuleController
{
    public function restIndexScaffold()
    {
        $adapter = Api::_()->getDbAdapter();
        
        $metadata = new Metadata($adapter);

        $tables = $metadata->getTableNames();

        return array(
            'tables' => $tables,
        );
    }
}
