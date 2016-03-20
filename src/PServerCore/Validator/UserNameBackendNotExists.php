<?php


namespace PServerCore\Validator;

use Exception;
use GameBackend\DataService\DataServiceInterface;
use PServerCore\Options\EntityOptions;
use Zend\Validator\AbstractValidator;

class UserNameBackendNotExists extends AbstractValidator
{
    const ERROR_RECORD_FOUND = 'recordFound';

    /**
     * TODO better message
     * @var array Message templates
     */
    protected $messageTemplates = [
        self::ERROR_RECORD_FOUND => "A record matching the input was found",
    ];

    /** @var  DataServiceInterface */
    protected $gameBackendService;

    /** @var  EntityOptions */
    protected $entityOptions;

    /**
     * UserNameBackendNotExists constructor.
     * @param DataServiceInterface $gameBackendService
     * @param EntityOptions $entityOptions
     */
    public function __construct(DataServiceInterface $gameBackendService, EntityOptions $entityOptions)
    {
        $this->gameBackendService = $gameBackendService;
        $this->entityOptions = $entityOptions;

        parent::__construct();
    }


    /**
     * @param mixed $value
     *
     * @return bool
     * @throws Exception
     */
    public function isValid($value)
    {
        $valid = true;
        $this->setValue($value);

        $result = $this->query($value);
        if ($result) {
            $valid = false;
            $this->error(self::ERROR_RECORD_FOUND);
        }

        return $valid;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    protected function query($value)
    {
        $class = $this->entityOptions->getUser();
        /** @var \PServerCore\Entity\UserInterface $user */
        $user = new $class();
        $user->setUsername($value);

        return $this->gameBackendService->isUserNameExists($user);
    }
}