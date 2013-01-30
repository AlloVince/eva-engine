<?php
require_once './autoloader.php';

$loader->registerNamespace('Symfony\\', EVA_LIB_PATH . '/Symfony/');
$appGlobelConfig = include EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.config.php';
$appLocalConfig = EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.local.config.php';
if(file_exists($appLocalConfig)){
    $appLocalConfig = include $appLocalConfig;
    $appGlobelConfig = array_merge($appGlobelConfig, $appLocalConfig);
}
Zend\Mvc\Application::init($appGlobelConfig);


use Symfony\Component\Process\Process;

$process = new Process('C:\Windows\System32\cmd.exe /V:ON /E:ON /C ""C:\Program Files\nodejs\node.exe" "C:\Users\AlloVince\AppData\Local\Temp\assB045.tmp""');
$process->setEnv(array('NODE_PATH' => 'C:\Users\AlloVince\AppData\Roaming\npm\node_modules'));
$process->setTimeout(3600);
$process->run();
if (!$process->isSuccessful()) {
    p($process);
}

print $process->getOutput();

