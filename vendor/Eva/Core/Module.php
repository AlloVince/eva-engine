<?php
namespace Eva\Core;
class Module
{
    public static function getModuleName($class)
    {
        $className = get_class($class);
        $moduleName = explode('\\', $className);
        $moduleName = strtolower($moduleName[0]);
        return $moduleName;
    }
}
