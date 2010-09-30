<?php
/**
 * @author Federico Marani
 * @package messaging
 * @copyright 2010 Federico Marani
 * @version SVN: $Id$
 */

/**
 * Replier pattern
 * 
 * @package messaging
 * @subpackage pattern
 */
abstract class messaging_pattern_Replier
{
    /**
     * @var messaging_Client
     */
    private $connection;
    
    /**
     * @var messaging_Destination
     */
    private $destination;

    /**
     * @param messaging_Destination $destination
     * @param messaging_Client $client
     */
    public function __construct(messaging_Destination $destination = null, messaging_Client $client = null)
    {
        if (!$client) {
            $client = new messaging_Client;
        }
        $this->connection = $client;
        
        if (!$destination) {
            $destination = messaging_destination_Factory::createQueue("defaultRequestQueue");
        }
        $this->destination = $destination;  
    }

    /**
     * @return void
     */
    public function init()
    {
        $this->connection->connect();
        $this->connection->subscribe($this->destination);        
    }

    /**
     * Serve requests. This function needs to be called from your event loop
     * 
     * @return void
     */
    public function serveRequest()
    {
        $msg = $this->connection->read();
        if (!$msg) return;
        $this->connection->acknowledge($msg);
            
        $replyMsg = $this->act($msg);

        // write correlation id
        $replyMsg->setHeader("correlation-id", $msg->getId());

        $this->connection->send($msg->getReplyTo(), $replyMsg);		
    }

    /**
     * Act on the message, returns answer as message
     * 
     * @param messaging_Message $message
     * @return messaging_Message
     */
    abstract public function act($message);
}

