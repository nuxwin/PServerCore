<?php


namespace PServerCore\Form;

use Doctrine\ORM\EntityManager;
use PServerCore\Options\Collection;
use PServerCore\Validator;
use PServerCore\Validator\AbstractRecord;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Hostname;

class AddEmailFilter extends InputFilter
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  array */
    protected $config;

    /** @var  Collection */
    protected $collectionOptions;

    /**
     * AddEmailFilter constructor.
     * @param EntityManager $entityManager
     * @param array $config
     * @param Collection $collection
     */
    public function __construct(EntityManager $entityManager, array $config, Collection $collection)
    {
        $this->entityManager = $entityManager;
        $this->config = $config;
        $this->collectionOptions = $collection;

        $this->add([
            'name' => 'email',
            'required' => true,
            'filters' => [['name' => 'StringTrim']],
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => Hostname::ALLOW_DNS,
                        'useMxCheck' => true,
                        'useDeepMxCheck' => true
                    ]
                ],
                $this->getStriposValidator()
            ],
        ]);

        if (!$collection->getRegisterOptions()->isDuplicateEmail()) {
            $element = $this->get('email');
            /** @var \Zend\Validator\ValidatorChain $chain */
            $chain = $element->getValidatorChain();
            $chain->attach($this->getEmailValidator());
            $element->setValidatorChain($chain);
        }

        $this->add([
            'name' => 'emailVerify',
            'required' => true,
            'filters' => [['name' => 'StringTrim']],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 255,
                    ],
                ],
                [
                    'name' => 'Identical',
                    'options' => [
                        'token' => 'email',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @return AbstractRecord
     */
    public function getEmailValidator()
    {
        /** @var $repositoryUser \Doctrine\Common\Persistence\ObjectRepository */
        $repositoryUser = $this->entityManager->getRepository(
            $this->collectionOptions->getEntityOptions()->getUser()
        );

        return new Validator\NoRecordExists($repositoryUser, 'email');
    }

    /**
     * @return Validator\StriposExists
     */
    public function getStriposValidator()
    {
        return new Validator\StriposExists($this->config, Validator\StriposExists::TYPE_EMAIL);
    }
}