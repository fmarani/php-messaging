<?php
/**
 * @package messaging
 * @copyright 2010 Federico Marani
 * @version SVN: $Id$
 */

/**
 * Simple data container for a message
 * 
 * @package messaging
 */
abstract class messaging_Message
{
    const CONTENT_TYPE_UNKNOWN = "text/unknown";
    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_TEXT = 'text/plain';
    
    /**
     * @var string
     */
    protected $contentType = self::CONTENT_TYPE_UNKNOWN;

    /**
     * @var mixed
     */
    private $body;
    
    /**
     * @var array
     */
    private $headers;
    
    /**
     * @var integer
     */
    private $id;
    
    /**
     * @param mixed $body
     * @param array $headers
     */
    public function __construct($body=null, array $headers=null)
    {
        $this->body = $body;
        if ($headers) $this->headers = $headers;
    }

    // =======
    // SETTERS
    // =======
    
    /**
     * @param string $id
     * @return messaging_Message
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @param string $body
     * @return message_Message
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return messaging_Message
     */
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }
    
    /**
     * @param array $headers
     * @return messaging_Message
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    // =======
    // GETTERS
    // =======
    
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getReplyTo()
    {
        return $this->getHeader('reply-to');
    }
    
    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }
    
    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
    
    /**
     * @param string $key
     * @return string
     */
    public function getHeader($key)
    {
        return isset($this->headers[$key]) ? $this->headers[$key] : null;
    }

    /**
     * @return string
     */
    abstract public function getSerializedBody();
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getBody();
    }
}
