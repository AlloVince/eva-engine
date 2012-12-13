<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Queue
 */

namespace Core\Queue\Adapter;

use MongoClient;
use ZendQueue\Adapter\AbstractAdapter;
use ZendQueue\Exception;
use ZendQueue\Message;
use ZendQueue\Queue;

/**
* Class for using connecting to a MongoDb
*
* @category   Zend
* @package    Zend_Queue
* @subpackage Adapter
*/
class Mongodb extends AbstractAdapter
{
    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT = 27017;
    const DEFAULT_DB = 'Eva_Queue';

    /**
    * @var \MongoClient
    */
    protected $mongodb = null;

    /**
    * @var string
    */
    protected $host = null;

    /**
    * @var integer
    */
    protected $port = null;

    /**
    * @var string
    */
    protected $db = null;

    /********************************************************************
    * Constructor / Destructor
    *********************************************************************/

    /**
    * Constructor
    *
    * @param  array|\Traversable $options
    * @param  null|\ZendQueue\Queue $queue
    * @return void
    */
    public function __construct($options, Queue $queue = null)
    {
        if (!extension_loaded('mongo')) {
            throw new Exception\ExtensionNotLoadedException('Mongodb extension does not appear to be loaded');
        }

        parent::__construct($options, $queue);

        $defaultOptions = array(
            'host' => self::DEFAULT_HOST,
            'port' => self::DEFAULT_PORT,
            'db' => self::DEFAULT_DB,
            'dsn' => '',
            'username' => '',
            'password' => '',
        );
        $options = &$this->_options['driverOptions'];
        $options = array_merge($defaultOptions, $options);

        $connectDsn = $options['dsn'] ? $options['dsn'] : "mongodb://{$options['host']}:{$options['port']}";
        $connectArray = array();
        if($options['username'] || $options['password']) {
            $connectArray = array(
                'username' => $options['username'],
                'password' => $options['password'],
            );
        }

        try {
            $this->mongo = $mongo = $connectArray ? new MongoClient($connectDsn, $connectArray) : new MongoClient($connectDsn);
            $mongo->selectDB($options['db']);
        } catch ( \MongoConnectionException $e ) {
            throw new Exception\ConnectionException('Could not connect to MongoDb');
        }

        $this->db = $options['db'];
        $this->host = $options['host'];
        $this->port = (int)$options['port'];
    }



    /**
    * Does a queue already exist?
    *
    * Throws an exception if the adapter cannot determine if a queue exists.
    * use isSupported('isExists') to determine if an adapter can test for
    * queue existance.
    *
    * @param  string $name
    * @return boolean
    * @throws \ZendQueue\Exception
    */
    public function isExists($name)
    {
        if (empty($this->_queues)) {
            $this->getQueues();
        }

        return in_array($name, $this->_queues);
    }

    /**
    * Create a new queue
    *
    * Visibility timeout is how long a message is left in the queue "invisible"
    * to other readers.  If the message is acknowleged (deleted) before the
    * timeout, then the message is deleted.  However, if the timeout expires
    * then the message will be made available to other queue readers.
    *
    * @param  string  $name    queue name
    * @param  integer $timeout default visibility timeout
    * @return boolean
    * @throws \ZendQueue\Exception
    */
    public function create($name, $timeout=null)
    {
        if ($this->isExists($name)) {
            return false;
        }

        if(preg_match('/[0-9a-zA-Z-_]+/', $name)){
            $this->_queues[] = $name;
            return true;
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                'Queue name %s not allow in mongodb', $name
            ));
        }
        return false;
    }

    /**
    * Delete a queue and all of it's messages
    *
    * Returns false if the queue is not found, true if the queue exists
    *
    * @param  string  $name queue name
    * @return boolean
    * @throws \ZendQueue\Exception
    */
    public function delete($name)
    {
        $mongo = $this->mongo;
        $collection = $mongo->selectCollection($this->db, $name);
        $response = $collection->drop();
        return $response['ok'] ? true : false;
    }

    /**
    * Get an array of all available queues
    *
    * Not all adapters support getQueues(), use isSupported('getQueues')
    * to determine if the adapter supports this feature.
    *
    * @return array
    * @throws \ZendQueue\Exception
    */
    public function getQueues()
    {
        $this->_queues = array();
        $mongo = $this->mongo;
        $this->_queues = $mongo->selectDB($this->db)->getCollectionNames();
        return $this->_queues;
    }

    /**
    * Return the approximate number of messages in the queue
    *
    * @param  \ZendQueue\Queue $queue
    * @return integer
    * @throws \ZendQueue\Exception (not supported)
    */
    public function count(Queue $queue=null)
    {
        if ($queue === null) {
            $queue = $this->_queue;
        }

        $mongo = $this->mongo;
        $collection = $mongo->selectCollection($this->db, $queue->getName());
        return $collection->count();
    }

    /********************************************************************
    * Messsage management functions
    *********************************************************************/

    /**
    * Send a message to the queue
    *
    * @param  string     $message Message to send to the active queue
    * @param  \ZendQueue\Queue $queue
    * @return \ZendQueue\Message
    * @throws \ZendQueue\Exception
    */
    public function send($message, Queue $queue = null)
    {
        if ($queue === null) {
            $queue = $this->_queue;
        }

        $mongo = $this->mongo;
        $collection = $mongo->selectCollection($this->db, $queue->getName());
        $data = array();
        if(is_string($message)){
            $data = array(
                'message' => $message,
            );
        } elseif(is_array($message)){
            $data = $message;
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                'Message allow string or array only'
            ));
        }

        $collection->insert($data);

        $options = array(
            'queue' => $queue,
            'data'  => $data,
        );
        $classname = $queue->getMessageClass();
        return new $classname($options);
    }


    public function batchSend($messages, $queue = null)
    {
    
    }

    /**
    * Get messages in the queue
    *
    * @param  integer    $maxMessages  Maximum number of messages to return
    * @param  integer    $timeout      Visibility timeout for these messages
    * @param  \ZendQueue\Queue $queue
    * @return \ZendQueue\Message\MessageIterator
    * @throws \ZendQueue\Exception
    */
    public function receive($maxMessages=null, $timeout=null, Queue $queue=null)
    {
        if ($maxMessages === null) {
            $maxMessages = 1;
        }

        if ($timeout === null) {
            $timeout = self::RECEIVE_TIMEOUT_DEFAULT;
        }
        if ($queue === null) {
            $queue = $this->_queue;
        }

        $mongo = $this->mongo;
        $collection = $mongo->selectCollection($this->db, $queue->getName());
        $msgs = $collection->find()->limit($maxMessages);
        $msgsArray = iterator_to_array($msgs);

        foreach($msgs as $msg){
            $collection->remove(array('_id' => $msg['_id']));
        }

        $options = array(
            'queue'        => $queue,
            'data'         => $msgsArray,
            'messageClass' => $queue->getMessageClass(),
        );
        $classname = $queue->getMessageSetClass();
        return new $classname($options);
    }

    /**
    * Delete a message from the queue
    *
    * Returns true if the message is deleted, false if the deletion is
    * unsuccessful.
    *
    * @param  \ZendQueue\Message $message
    * @return boolean
    * @throws \ZendQueue\Exception (unsupported)
    */
    public function deleteMessage(Message $message)
    {
        throw new Exception\UnsupportedMethodCallException('deleteMessage() is not supported in  ' . get_called_class());
    }

    /********************************************************************
    * Supporting functions
    *********************************************************************/

    /**
    * Return a list of queue capabilities functions
    *
    * $array['function name'] = true or false
    * true is supported, false is not supported.
    *
    * @param  string $name
    * @return array
    */
    public function getCapabilities()
    {
        return array(
            'create'        => true,
            'delete'        => true,
            'send'          => true,
            'receive'       => true,
            'deleteMessage' => false,
            'getQueues'     => true,
            'count'         => true,
            'isExists'      => true,
        );
    }
}
