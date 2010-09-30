<?php
/**
 * @package messaging
 * @copyright 2010 Federico Marani
 * @version SVN: $Id$
 */

require_once dirname(__FILE__).'/../stomp/Stomp.php';

/**
 * Messaging client (STOMP-based)
 * 
 * @package messaging
 */
class messaging_Client
{
    /**
     * @var Stomp
     */
    private $stomp;
    
    /**
     * @var messaging_message_Factory
     */
    private $messageFactory;
    
    /**
     * @param string $url
     * @param messaging_message_Factory $messageFactory
     * @throws Stomp_Exception
     */
    public function __construct($url='tcp://localhost:61613', messaging_message_Factory $messageFactory=null)
    {
        $this->stomp = new Stomp($url);
        // $this->stomp->sync = true; // works async by default, sync causes problems
        $this->messageFactory = ($messageFactory) ? $messageFactory : new messaging_message_Factory;
    }
    
    /**
     * @return void
     */
    public function __destruct()
    {
        $this->disconnect();
    }
    
    /**
     * Setter for unit-testing to allow the stomp client to be mocked.
     * 
     * @param Stomp $stomp
     * @return message_StompClient
     */
    public function setStomp($stomp)
    {
        $this->stomp = $stomp;
        return $this;
    }
    
    /**
     * @param float $timeout
     * @return message_StompClient
     */
    public function setTimeoutInSeconds($timeout)
    {
        $seconds = intval(floor($timeout));
        $microseconds = intval(($timeout - $seconds)*1000000);
        $this->stomp->setReadTimeout($seconds, $microseconds);
        return $this;
    }
    
    /**
     * @param string $username
     * @param string $password
     * @return message_StompClient
     */
    public function connect($username=null, $password=null)
    {
        if (!is_null($username) && !is_null($password)) {
            $this->stomp->connect($username, $password);
        } else {
            $this->stomp->connect();
        }
        return $this;
    }
    
    /**
     * @return void
     */
    public function disconnect()
    {
        $this->stomp->disconnect();
    }
    
    /**
     * @param messaging_Destination|string $destination
     * @param messaging_Message $message
     * @return messaging_Client
     */
    public function send($destination, messaging_Message $message)
    {
    	$body = $message->getSerializedBody();
    	$headers = array();

    	$headers['content-type'] = $message->getContentType();
    	//$headers['content-length'] = strlen($body);

        // persistency forces queue server to store msg to disk
        if ($destination instanceof messaging_destination_PersistentQueue) {
            $headers['persistent'] = "true";
        }

        // pass TTL value to the server (after made it absolute and added millisecs)
        $ttl = $message->getHeader("expires");
        if ($ttl) {
            $headers['expires'] = strval(time() + $ttl) . "000";
        }

        // used by request-reply pattern
        $replyTo = $message->getHeader("reply-to");
        if ($replyTo) {
            $headers['reply-to'] = $replyTo;
        }
        $correlationId = $message->getHeader("correlation-id");
    	if ($correlationId) {
    		$headers['correlation-id'] = $correlationId;
    	}

        // strings should never been passed, but the replier needs to inject arbitrary queue names..
        if (gettype($destination) == "string") {
            $stompDest = $destination;
        } else {
            $stompDest = $destination->getRealName();
        }

        $this->stomp->send($stompDest, $body, $headers);
        return $this;
    }
    
    /**
     * @param string $message
     * @return message_StompClient
     */
    public function acknowledge(messaging_Message $message)
    {
        $this->stomp->ack($message->getId());
        return $this;
    }
    
    /**
     * Subscribe to a given queue/topic
     * 
     * @param string $destination
     * @return message_StompClient
     */
    public function subscribe(messaging_Destination $destination)
    {
        $this->stomp->subscribe($destination->getRealName());
        return $this;
    }
    
    /**
     * Unsubscribe from a given queue/topic
     * 
     * @param string $destination
     * @return message_StompClient
     */
    public function unsubscribe(messaging_Destination $destination)
    {
        $this->stomp->unsubscribe($destination->getRealName());
        return $this;
    }
    
    /**
     * @return Stomp_Frame
     */
    public function read()
    {
        $frame = $this->stomp->readFrame();
        if (!$frame) return null;
        return $message = $this->messageFactory->createMessageFromFrame($frame);
    }
    
    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->stomp->getSessionId();
    }
}
