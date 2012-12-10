<?php
require_once './autoloader.php';

$appGlobelConfig = include EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.config.php';
$appLocalConfig = EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.local.config.php';
if(file_exists($appLocalConfig)){
    $appLocalConfig = include $appLocalConfig;
    $appGlobelConfig = array_merge($appGlobelConfig, $appLocalConfig);
}
Zend\Mvc\Application::init($appGlobelConfig);

// For configuration options
// @see Zend_Queue_Adapater::__construct()
$options = array(
    'name' => 'queue2',
    'adapterNamespace' => 'Core\Queue\Adapter',
    'driverOptions' => array(
        'username' => 1,
    ),
);

// Create an array queue
$queue = new \ZendQueue\Queue('Mongodb', $options);

$queue2 = $queue->createQueue('queue2');

// Get list of queues

$queue->send('My Test Message' . time());

$messages = $queue->receive(1);
p($messages);
p($queue->count());

/*
// Create a new queue

// Get number of messages in a queue (supports Countable interface from SPL)
echo count($queue);

// Get up to 5 messages from a queue

foreach ($messages as $i => $message) {
    echo $message->body, "\n";

    // We have processed the message; now we remove it from the queue.
    $queue->deleteMessage($message);
}

// Send a message to the currently active queue
$queue->send('My Test Message');

// Delete a queue we created and all of it's messages
$queue->deleteQueue('queue2');
*/

