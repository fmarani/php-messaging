<?php
/**
 * @package messaging
 * @copyright 2010 Federico Marani
 * @version SVN: $Id$
 */

/**
 * JSON data container for a message
 * 
 * @package messaging
 * @subpackage message
 */
class messaging_message_JsonMessage extends messaging_Message
{
    /**
     * @var string
     */
	protected $contentType = messaging_Message::CONTENT_TYPE_JSON;

	/**
	 * @return string
	 */
	public function getSerializedBody()
	{
		return json_encode($this->getBody());
	}
}
