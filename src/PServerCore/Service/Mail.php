<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use Exception;
use PServerCore\Entity\UserInterface;
use PServerCore\Options\Collection;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface;
use ZfcTicketSystem\Entity\TicketEntry;
use ZfcTicketSystem\Entity\TicketSubject;

class Mail
{
    const SUBJECT_KEY_REGISTER = 'register';
    const SUBJECT_KEY_PASSWORD_LOST = 'password';
    const SUBJECT_KEY_CONFIRM_COUNTRY = 'country';
    const SUBJECT_KEY_SECRET_LOGIN = 'secretLogin';
    const SUBJECT_KEY_TICKET_ANSWER = 'ticketAnswer';
    const SUBJECT_KEY_ADD_EMAIL = 'addEmail';

    /** @var  RendererInterface */
    protected $viewRenderer;

    /** @var  Collection */
    protected $collectionOptions;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var SmtpOptions */
    protected $mailSMTPOptions;

    /**
     * Mail constructor.
     * @param RendererInterface $viewRenderer
     * @param Collection $collectionOptions
     * @param EntityManager $entityManager
     */
    public function __construct(
        RendererInterface $viewRenderer,
        Collection $collectionOptions,
        EntityManager $entityManager
    ) {
        $this->viewRenderer = $viewRenderer;
        $this->collectionOptions = $collectionOptions;
        $this->entityManager = $entityManager;
    }

    /**
     * RegisterMail
     *
     * @param UserInterface $user
     * @param       $code
     */
    public function register(UserInterface $user, $code)
    {
        $params = [
            'user' => $user,
            'code' => $code
        ];

        $this->send(self::SUBJECT_KEY_REGISTER, $user, $params);
    }

    /**
     * @param UserInterface $user
     * @param       $code
     */
    public function lostPw(UserInterface $user, $code)
    {
        $params = [
            'user' => $user,
            'code' => $code
        ];

        $this->send(self::SUBJECT_KEY_PASSWORD_LOST, $user, $params);
    }

    /**
     * @param UserInterface $user
     * @param $code
     */
    public function confirmCountry(UserInterface $user, $code)
    {
        $params = [
            'user' => $user,
            'code' => $code
        ];

        $this->send(self::SUBJECT_KEY_CONFIRM_COUNTRY, $user, $params);
    }

    /**
     * @param UserInterface $user
     * @param $code
     */
    public function secretLogin(UserInterface $user, $code)
    {
        $params = [
            'user' => $user,
            'code' => $code
        ];

        $this->send(self::SUBJECT_KEY_SECRET_LOGIN, $user, $params);
    }

    /**
     * @param UserInterface $user
     * @param TicketSubject $ticketSubject
     * @param TicketEntry $ticketEntry
     */
    public function ticketAnswer(UserInterface $user, TicketSubject $ticketSubject, TicketEntry $ticketEntry)
    {
        $params = [
            'user' => $user,
            'ticketSubject' => $ticketSubject,
            'ticketEntry' => $ticketEntry,
        ];

        $this->send(self::SUBJECT_KEY_TICKET_ANSWER, $user, $params);
    }

    /**
     * @param UserInterface $user
     * @param $code
     */
    public function addEmail(UserInterface $user, $code)
    {
        $params = [
            'user' => $user,
            'code' => $code
        ];

        $this->send(self::SUBJECT_KEY_ADD_EMAIL, $user, $params);
    }

    /**
     * @param $subjectKey
     * @param UserInterface $user
     * @param $params
     */
    protected function send($subjectKey, UserInterface $user, $params)
    {
        // we have no mail, so we can skip it
        if (!$user->getEmail()) {
            return;
        }

        // TODO TwigTemplateEngine
        $renderer = $this->viewRenderer;
        //$oResolver = $this->getServiceManager()->get('ZfcTwig\View\TwigResolver');
        //$oResolver->resolve(__DIR__ . '/../../../view');
        //$oRenderer->setResolver($oResolver);

        //$oRenderer->setVars($aParams);
        $viewModel = new ViewModel();
        $viewModel->setTemplate('email/tpl/' . $subjectKey);
        $viewModel->setVariables($params);

        $bodyRender = $renderer->render($viewModel);

        $subject = $this->getSubject4Key($subjectKey);

        try {
            // make a header as html
            $html = new Part($bodyRender);
            $html->type = "text/html";
            $body = new MimeMessage();
            $body->setParts([$html]);

            $mail = new Message();
            $mail->setBody($body);
            $mailOptions = $this->collectionOptions->getMailOptions();
            $mail->setFrom($mailOptions->getFrom(), $mailOptions->getFromName());
            $mail->setTo($user->getEmail());
            $mail->setSubject($subject);

            // sometimes we want to log all emails
            if ($this->collectionOptions->getMailOptions()->isDebug()) {
                $this->logMail($user, $bodyRender, 'debug');
            }

            $transport = new Smtp($this->getSMTPOptions());
            $transport->send($mail);
        } catch (Exception $e) {
            // Logging if smth wrong in Configuration or SMTP Offline =)
            $this->logMail($user, $e->getMessage(), 'faild');
        }
    }

    /**
     * @return SmtpOptions
     */
    public function getSMTPOptions()
    {
        if (!$this->mailSMTPOptions) {
            $this->mailSMTPOptions = new SmtpOptions($this->collectionOptions->getMailOptions()->getBasic());
        }

        return $this->mailSMTPOptions;
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function getSubject4Key($key)
    {
        $subjectList = $this->collectionOptions->getMailOptions()->getSubject();
        // added fallback if the key not exists, in the config
        return $subjectList[$key] ?? $key;
    }

    /**
     * @param UserInterface $user
     * @param string $message
     * @param string $topic
     */
    protected function logMail(UserInterface $user, $message, $topic)
    {
        $class = $this->collectionOptions->getEntityOptions()->getLogs();
        /** @var \PServerCore\Entity\Logs $logEntity */
        $logEntity = new $class();
        $logEntity->setTopic('mail_' . $topic);
        $logEntity->setMemo($message);
        $logEntity->setUser($user);
        $this->entityManager->persist($logEntity);
        $this->entityManager->flush();
    }
}