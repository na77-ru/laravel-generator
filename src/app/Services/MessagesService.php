<?php

namespace AlexClaimer\Generator\App\Services;

/**
 * Class MessagesService
 * @package App\Services
 */
class MessagesService
{
    /**
     * @var array
     */
    protected $messages;
    /**
     * @var array
     */
    protected $alert_type;

    /**
     * @return mixed
     */
    public function getMsg()
    {
        return $this->messages;
    }

    /**
     * @param mixed $messages
     */
    public function setMsg($messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param mixed $messages
     */
    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @return mixed
     */
    public function getAT()
    {
        return $this->alert_type;
    }

    /**
     * @param mixed $alert_type
     */
    public function setAT($alert_type): void
    {
        $this->alert_type = $alert_type;
    }

    /**
     * @return mixed
     */
    public function getAlertType()
    {
        return $this->alert_type;
    }

    /**
     * @param mixed $alert_type
     */
    public function setAlertType($alert_type): void
    {
        $this->alert_type = $alert_type;
    }
}
