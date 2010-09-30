<?php
/**
 * @package messaging
 * @subpackage destination
 * @copyright 2010 Federico Marani
 * @version SVN: $Id$
 */

/**
 * Destination factory
 * 
 * @package messaging
 * @subpackage destination
 */
class messaging_destination_Factory
{
    private static function getNamespacedName($name)
    {
        // insert logic to generate namespace
        $appName = "default";
        
        $appName = strtolower(str_replace(' ', '', $appName));
        return $appName.".".$name; // use dot as a hierarchy separator
    }

    public static function createQueue($name)
    {
        return new messaging_destination_Queue(self::getNamespacedName($name));
    }
    
    public static function createPersistentQueue($name)
    {
        return new messaging_destination_PersistentQueue(self::getNamespacedName($name));
    }
    
    public static function createTopic($name)
    {
        return new messaging_destination_Topic(self::getNamespacedName($name));
    }
}
