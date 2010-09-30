<?php
/**
 * @author Federico Marani
 * @package messaging
 * @copyright 2010 Federico Marani
 * @version SVN: $Id$
 */

/**
 * Fire and Forget integration pattern
 * 
 * @package messaging
 * @subpackage pattern
 */
class messaging_pattern_FireAndForget
{
    /**
     * @var messaging_Client
     */
    private $connection;
    
    /**
     * @var messaging_pattern_FireAndForget
     */
    private static $pattern;
    
    
    /**
     * @param messaging_Client $client
     */
    public function __construct(messaging_Client $client)
    {
        $this->connection = $client;
        $this->connection->connect();
    }

    /**
     * Fire the request
     * 
     * @param messaging_Message $message
     * @return void
     */
    public function sendMessage(messaging_Message $message, messaging_Destination $destination=null)
    {
        if (!$destination) {
            $destination = messaging_destination_Factory::createQueue("defaultEventQueue");
        } elseif (gettype($destination) == "string") {
            $destination = messaging_destination_Factory::createQueue($destination);
        }
        $this->connection->send($destination, $message);
    }
    
    /**
     * Fire the request
     * 
     * @param messaging_Message $message
     * @return boolean
     */
    public static function send(messaging_Message $message, messaging_Destination $destination=null)
    {
        try {
            if (!self::$pattern) {
                self::$pattern = new self(new messaging_Client);
            }
            self::$pattern->sendMessage($message, $destination);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}

