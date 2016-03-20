<?php

namespace PServerCore\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class StriposExists extends AbstractValidator
{
    const TYPE_EMAIL = 'email';

    /**
     * Error constants
     */
    const ERROR_BLACKLIST = 'EntryNotAllowed';

    /**
     * TODO better message
     * @var array Message templates
     */
    protected $messageTemplates = [
        self::ERROR_BLACKLIST => "Entry not allowed"
    ];

    /** @var array */
    protected $config;
    /** @var  string */
    protected $type;

    /**
     * @param array $config
     * @param string $type
     */
    public function __construct(array $config, $type)
    {
        $this->config = $config;
        $this->setType($type);

        parent::__construct();
    }

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     *
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        $result = true;
        $this->setValue($value);
        $blackList = $this->config['blacklisted'][$this->getType()];

        if (!$blackList) {
            return $result;
        }

        foreach ($blackList as $entry) {
            if (stripos($value, $this->editBlackListedData($entry)) === false) {
                continue;
            }
            $result = false;
            $this->error(self::ERROR_BLACKLIST);
            break;
        }

        return $result;
    }

    /**
     * @param $type
     */
    protected function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    protected function getType()
    {
        return $this->type;
    }

    /**
     * @param $data
     *
     * @return string
     */
    protected function editBlackListedData($data)
    {
        if ($this->getType() == self::TYPE_EMAIL) {
            $data = sprintf('@%s', $data);
        }

        return $data;
    }
}