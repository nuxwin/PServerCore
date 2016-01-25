<?php
namespace PServerCore\Service;

use GameBackend\Helper\Service;
use PServerCore\Helper\HelperBasic;
use PServerCore\Helper\HelperForm;
use PServerCore\Helper\HelperOptions;
use PServerCore\Helper\HelperService;
use Zend\Form\FormInterface;
use Zend\ServiceManager\ServiceManager as ZendServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

abstract class InvokableBase implements ServiceManagerAwareInterface
{
    use HelperService, HelperOptions, Service, HelperForm, HelperBasic;

    /** @var ZendServiceManager */
    protected $serviceManager;
    /** @var \Zend\Mvc\Controller\Plugin\FlashMessenger */
    protected $flashMessenger;

    /**
     * @return ZendServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param ZendServiceManager $serviceManager
     *
     * @return $this
     */
    public function setServiceManager(ZendServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    /**
     * @return \Zend\Mvc\Controller\Plugin\FlashMessenger
     */
    protected function getFlashMessenger()
    {
        if (!$this->flashMessenger) {
            $this->flashMessenger = $this->getControllerPluginManager()->get('flashMessenger');
        }

        return $this->flashMessenger;
    }

    /**
     * @param $userId
     *
     * @return null|\PServerCore\Entity\UserInterface
     */
    protected function getUser4Id($userId)
    {
        /** @var \PServerCore\Entity\Repository\User $userRepository */
        $userRepository = $this->getEntityManager()->getRepository($this->getEntityOptions()->getUser());

        return $userRepository->getUser4Id($userId);
    }

    /**
     * @param FormInterface $form
     * @param $namespace
     */
    protected function addFormMessagesInFlashMessenger(FormInterface $form, $namespace)
    {
        $messages = $form->getMessages();
        foreach ($messages as $elementMessages) {
            foreach ($elementMessages as $message) {
                $this->getFlashMessenger()->setNamespace($namespace)->addMessage($message);
            }
        }
    }
} 