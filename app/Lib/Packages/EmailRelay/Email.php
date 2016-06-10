<?php

namespace App\Lib\Packages\EmailRelay;

/**
 * Class Email
 * @package App\Lib\Packages\EmailRelay
 */
class Email {

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $sender;

    /**
     * @param string $body
     * @return $this
     */
    public function setBody(string $body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $recipient
     * @return $this
     */
    public function setRecipient(string $recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $sender
     * @return $this
     */
    public function setSender(string $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }
}