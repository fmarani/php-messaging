<?php
/**
 * @package messaging
 * @copyright 2010 Federico Marani
 * @version SVN: $Id$
 */

/**
 * Interface for queue destinations
 * 
 * @package messaging
 */
abstract class messaging_Destination
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var boolean
     */
    protected $isTemporary = false;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
    
    /**
     * @return boolean
     */
    public function setAsTemporary()
    {
        $this->isTemporary = true;
        return $this;
    }
    
    /**
     * @return string
     */
    abstract public function getRealName();

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
