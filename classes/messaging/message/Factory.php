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
class messaging_message_Factory
{
    /**
     * @param Stomp_Frame $frame
     * @return messaging_Message
     */
	public function createMessageFromFrame($frame)
	{
	    switch ($frame->headers['content-type']) {
        case messaging_Message::CONTENT_TYPE_JSON:
            $message = new messaging_message_JsonMessage(json_decode($frame->body, true));
            break;
        default:
            // treat messages without content-type as text/plain
            $message = new messaging_message_TextMessage($frame->body);
            break;
        }
        $message->setId($frame->headers['message-id']);
        $message->setHeaders($frame->headers);
        return $message;
	}
}
