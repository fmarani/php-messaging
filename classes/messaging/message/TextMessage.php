<?php
/**
 * @author Federico Marani
 * @package messaging
 * @copyright 2010 Federico Marani
 * @version SVN: $Id$
 */

/**
 * Text data container for a message
 * 
 * @package messaging
 * @subpackage message
 */
class messaging_message_TextMessage extends messaging_Message
{
    /**
     * @var string
     */
	protected $contentType = messaging_Message::CONTENT_TYPE_TEXT;

    /**
     * @return string
     */
	public function getSerializedBody()
	{
		return strval($this->getBody());
	}
}
