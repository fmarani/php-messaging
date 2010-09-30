<?php
/**
 * @author Federico Marani
 * @package messaging
 * @copyright 2010 Federico Marani
 * @version SVN: $Id$
 */

/**
 * Requester pattern
 * 
 * @package messaging
 * @subpackage pattern
 */
class messaging_pattern_Requester
{
    // cancel requests from the queue if not processed in 5 minutes
    const REQUEST_TIMETOLIVE = 300;

    /**
     * @var messaging_Destination
     */
    private $destination;
    
    /**
     * @var messaging_Client
     */
    private $connection;
    
    /**
     * @var messaging_Destination
     */
    private $replyToDestination;
    
    /**
     * @var string
     */
    private $replyToDestinationName;

    /**
     * @param messaging_Destination $destination
     * @param messaging_Client $client
     */
    public function __construct(messaging_Destination $destination = null, messaging_Client $client = null)
    {
        if ($client) {
            $this->connection = $client;
        } else {
            $this->connection = new messaging_Client;
        }
        $this->connection->connect();
        if (!$destination) {
            $destination = messaging_destination_Factory::createQueue("defaultRequestQueue");
        }
        $this->destination = $destination;  
        
        $this->replyToDestinationName = $this->destination->getName()."Reply-".uniqid();
        $this->replyToDestination = new messaging_destination_Queue($this->replyToDestinationName);
        $this->replyToDestination->setAsTemporary();
        $this->connection->subscribe($this->replyToDestination);
    }

    /**
     * Send the request
     * 
     * @param messaging_Message $message
     * @return messaging_Message
     */
    public function sendRequest(messaging_Message $message)
    {
        // set return address to reply queue
        $message->setHeader("reply-to", $this->replyToDestination->getRealName());

        $message->setHeader("expires", self::REQUEST_TIMETOLIVE);

        return $this->connection->send($this->destination, $message);
    }
    
    /**
     * Wait the reply. DO NOT ASSUME THIS WILL ALWAYS RETURN!!
     * 
     * @param messaging_Message $message
     * @return messaging_Message
     */
    public function waitReply()
    {
        return $this->connection->read();
    }
}
