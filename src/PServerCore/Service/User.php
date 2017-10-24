<?php

namespace PServerCore\Service;

use DateInterval, DateTime;
use Doctrine\ORM\EntityManager;
use GameBackend\DataService\DataServiceInterface;
use PServerCore\Entity\Repository\AvailableCountries as RepositoryAvailableCountries;
use PServerCore\Entity\Repository\CountryList;
use PServerCore\Entity\User as Entity;
use PServerCore\Entity\UserCodes as UserCodesEntity;
use PServerCore\Entity\UserInterface;
use PServerCore\Options\Collection;
use PServerCore\Service\UserCodes as UserCodesService;
use PServerCore\Validator\AbstractRecord;
use SmallUser\Entity\UserInterface as SmallUserInterface;
use SmallUser\Mapper\HydratorUser;
use SmallUser\Service\User as SmallUser;
use Zend\Crypt\Password\Bcrypt;
use Zend\Form\FormInterface;
use Zend\Validator\EmailAddress;

/**
 * Class User
 * @package PServerCore\Service
 * @TODO refactoring
 */
class User extends SmallUser
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Collection */
    protected $collectionOptions;

    /** @var  Ip */
    protected $ipService;

    /** @var  Mail */
    protected $mailService;

    /** @var  UserCodesService */
    protected $userCodeService;

    /** @var  DataServiceInterface */
    protected $gameDataService;

    /** @var  SecretQuestion */
    protected $secretQuestionService;

    /** @var UserBlock */
    protected $userBlockService;

    /** @var  FormInterface */
    protected $registerForm;

    /** @var  FormInterface */
    protected $passwordForm;

    /** @var  FormInterface */
    protected $passwordLostForm;

    /**
     * @param array $data
     * @return bool
     */
    public function login(array $data)
    {
        $result = parent::login($data);
        if (!$result) {
            $form = $this->getLoginForm();
            $error = $form->getMessages('username');
            if ($error && isset($error[AbstractRecord::ERROR_NOT_ACTIVE])) {
                $this->getFlashMessenger()->setNamespace($this::ERROR_NAME_SPACE)->addMessage($error[AbstractRecord::ERROR_NOT_ACTIVE]);
            }
        }

        return $result;
    }

    /**
     * @param array $data
     * @return UserInterface|bool
     */
    public function register(array $data)
    {
        $form = $this->registerForm;
        $form->setHydrator(new HydratorUser());
        $form->bind(new Entity());
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }

        $entityManager = $this->entityManager;
        /** @var Entity $userEntity */
        $userEntity = $form->getData();
        $userEntity->setCreateIp($this->ipService->getIp());
        $plainPassword = $userEntity->getPassword();
        $userEntity->setPassword($this->bCrypt($plainPassword));

        $entityManager->persist($userEntity);
        $entityManager->flush();

        if ($this->isRegisterMailConfirmationOption()) {
            $code = $this->userCodeService->setCode4User($userEntity, UserCodesEntity::TYPE_REGISTER);

            $this->mailService->register($userEntity, $code);
        } else {
            $userEntity = $this->registerGame($userEntity, $plainPassword);
            $this->setAvailableCountries4User($userEntity, $this->ipService->getIp());
            //valid identity after register with no mail
            $this->doAuthentication($userEntity);
        }

        if ($this->isSecretQuestionOption()) {
            $this->secretQuestionService->setSecretAnswer($userEntity, $data['question'], $data['answer']);
        }

        return $userEntity;
    }

    /**
     * @param array $data
     * @param UserCodesEntity $userCode
     * @return UserInterface|bool
     */
    public function registerGameWithOtherPw(array $data, UserCodesEntity $userCode)
    {
        $form = $this->passwordForm;

        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }

        $data = $form->getData();
        $plainPassword = $data['password'];
        $user = $this->registerGameForm($userCode, $plainPassword);

        return $user;
    }

    /**
     * @param UserCodesEntity $userCode
     * @param null $plainPassword
     * @return UserInterface
     */
    public function registerGameForm(UserCodesEntity $userCode, $plainPassword = null)
    {
        $user = $this->registerGame($userCode->getUser(), $plainPassword);
        $this->setAvailableCountries4User($user, $this->ipService->getIp());

        if ($user) {
            $this->userCodeService->deleteCode($userCode);
            //user logged-in after confirmation
            $this->doAuthentication($user);
        }

        return $user;
    }

    /**
     * @param array $data
     * @return bool|null|UserInterface
     */
    public function lostPw(array $data)
    {
        $form = $this->passwordLostForm;
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        $data = $form->getData();

        /** @var \PServerCore\Entity\Repository\User $userRepository */
        $userRepository = $this->entityManager->getRepository($this->collectionOptions->getEntityOptions()->getUser());
        $user = $userRepository->getUser4UserName($data['username']);

        $code = $this->userCodeService->setCode4User($user, UserCodesEntity::TYPE_LOST_PASSWORD);

        $this->mailService->lostPw($user, $code);

        return $user;
    }

    /**
     * @param array $data
     * @param UserCodesEntity $userCode
     * @return bool|UserInterface
     */
    public function lostPwConfirm(array $data, UserCodesEntity $userCode)
    {
        $form = $this->passwordForm;
        /** @var \PServerCore\Form\PasswordFilter $filter */
        $filter = $form->getInputFilter();
        if ($this->getEntityManagerAnswer()->getAnswer4UserId($userCode->getUser()->getId())) {
            $filter->addAnswerValidation($userCode->getUser());
        }
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }

        $data = $form->getData();
        $plainPassword = $data['password'];
        $userEntity = $userCode->getUser();

        $this->setNewPasswordAtUser($userEntity, $plainPassword);

        $this->userCodeService->deleteCode($userCode);

        if ($this->isSamePasswordOption()) {
            $gameBackend = $this->gameDataService;
            $gameBackend->setUser($userEntity, $plainPassword);
        }

        return $userEntity;
    }

    /**
     * @param UserCodesEntity $userCodes
     * @return UserInterface
     */
    public function countryConfirm(UserCodesEntity $userCodes)
    {
        $entityManager = $this->entityManager;

        /** @var UserInterface $userEntity */
        $userEntity = $userCodes->getUser();
        $this->setAvailableCountries4User($userEntity, $this->ipService->getIp());

        $entityManager->remove($userCodes);
        $entityManager->flush();

        return $userEntity;
    }

    /**
     * @param $userId
     * @return null|UserInterface
     */
    public function getUser4Id($userId)
    {
        /** @var \PServerCore\Entity\Repository\User $userRepository */
        $userRepository = $this->entityManager->getRepository($this->collectionOptions->getEntityOptions()->getUser());

        return $userRepository->getUser4Id($userId);
    }

    /**
     * @param EntityManager $entityManager
     * @return self
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @param Collection $collectionOptions
     * @return self
     */
    public function setCollectionOptions($collectionOptions)
    {
        $this->collectionOptions = $collectionOptions;
        return $this;
    }

    /**
     * @param Ip $ipService
     * @return self
     */
    public function setIpService($ipService)
    {
        $this->ipService = $ipService;
        return $this;
    }

    /**
     * @param Mail $mailService
     * @return self
     */
    public function setMailService($mailService)
    {
        $this->mailService = $mailService;
        return $this;
    }

    /**
     * @param UserCodes $userCodeService
     * @return self
     */
    public function setUserCodeService($userCodeService)
    {
        $this->userCodeService = $userCodeService;
        return $this;
    }

    /**
     * @param DataServiceInterface $gameDataService
     * @return self
     */
    public function setGameDataService($gameDataService)
    {
        $this->gameDataService = $gameDataService;
        return $this;
    }

    /**
     * @param SecretQuestion $secretQuestionService
     * @return self
     */
    public function setSecretQuestionService($secretQuestionService)
    {
        $this->secretQuestionService = $secretQuestionService;
        return $this;
    }

    /**
     * @param UserBlock $userBlockService
     * @return self
     */
    public function setUserBlockService($userBlockService)
    {
        $this->userBlockService = $userBlockService;
        return $this;
    }

    /**
     * @param FormInterface $registerForm
     * @return self
     */
    public function setRegisterForm($registerForm)
    {
        $this->registerForm = $registerForm;
        return $this;
    }

    /**
     * @param FormInterface $passwordLostForm
     * @return self
     */
    public function setPasswordLostForm($passwordLostForm)
    {
        $this->passwordLostForm = $passwordLostForm;
        return $this;
    }

    /**
     * @param FormInterface $passwordForm
     * @return self
     */
    public function setPasswordForm($passwordForm)
    {
        $this->passwordForm = $passwordForm;
        return $this;
    }

    /**
     * @param UserInterface $user
     * @param string $plainPassword
     * @return UserInterface
     */
    protected function registerGame(UserInterface $user, $plainPassword = '')
    {
        $gameBackend = $this->gameDataService;

        $backendId = $gameBackend->setUser($user, $plainPassword);
        $user->setBackendId($backendId);

        $entityManager = $this->entityManager;
        /** user have already a backendId, so better to set it there */
        $entityManager->persist($user);
        $entityManager->flush();

        $user = $this->addDefaultRole($user);

        return $user;
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    protected function addDefaultRole(UserInterface $user)
    {
        $entityManager = $this->entityManager;
        /** @var \PServerCore\Entity\Repository\UserRole $repositoryRole */
        $repositoryRole = $entityManager->getRepository($this->collectionOptions->getEntityOptions()->getUserRole());
        $role = $this->collectionOptions->getRegisterOptions()->getRole();
        /** @var \PServerCore\Entity\UserRoleInterface $roleEntity */
        $roleEntity = $repositoryRole->getRole4Name($role);

        // add the ROLE + Remove the Key
        $user->addUserRole($roleEntity);
        $roleEntity->addUser($user);
        $entityManager->persist($user);
        $entityManager->persist($roleEntity);
        $entityManager->flush();

        return $user;
    }

    /**
     * @param UserInterface $user
     * @param string $ip
     */
    protected function setAvailableCountries4User(UserInterface $user, $ip)
    {
        // skip if the config say no check, so we don´t have to save the country in list
        if (!$this->collectionOptions->getLoginOptions()->isCountryCheck()) {
            return;
        }

        $entityManager = $this->entityManager;
        /** @var CountryList $countryList */
        $countryList = $entityManager->getRepository($this->collectionOptions->getEntityOptions()->getCountryList());
        $class = $this->collectionOptions->getEntityOptions()->getAvailableCountries();
        /** @var \PServerCore\Entity\AvailableCountries $availableCountries */
        $availableCountries = new $class;
        $availableCountries->setUser($user);
        $availableCountries->setCntry($countryList->getCountryCode4Ip($this->ipService->getIp2Decimal($ip)));
        $entityManager->persist($availableCountries);
        $entityManager->flush();
    }

    /**
     * @param SmallUserInterface|UserInterface $user
     * @return bool
     */
    protected function isValidLogin(SmallUserInterface $user)
    {
        $result = true;

        if ($this->collectionOptions->getLoginOptions()->isCountryCheck() && !$this->isCountryAllowed($user)) {
            $result = false;
        }

        if ($result && $this->isUserBlocked($user)) {
            $result = false;
        }

        if ($result && $this->isSecretLogin($user)) {
            $result = false;
        }

        return $result;
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    protected function isCountryAllowed(UserInterface $user)
    {
        $result = true;
        $entityManager = $this->entityManager;

        /** @var CountryList $countryList */
        $countryList = $entityManager->getRepository($this->collectionOptions->getEntityOptions()->getCountryList());
        $country = $countryList->getCountryCode4Ip($this->ipService->getIp2Decimal());
        /** @var RepositoryAvailableCountries $availableCountries */
        $availableCountries = $entityManager->getRepository($this->collectionOptions->getEntityOptions()->getAvailableCountries());

        if (!$availableCountries->isCountryAllowedForUser($user->getId(), $country)) {
            $code = $this->userCodeService->setCode4User($user, UserCodesEntity::TYPE_CONFIRM_COUNTRY);
            $this->mailService->confirmCountry($user, $code);
            $this->getFlashMessenger()->setNamespace($this::ERROR_NAME_SPACE)->addMessage('Please confirm your new ip with your email');
            $result = false;
        }

        return $result;
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    protected function isSecretLogin(UserInterface $user)
    {
        $result = false;
        $secretLoginRoleList = $this->collectionOptions->getLoginOptions()->getSecretLoginRoleList();

        if ($secretLoginRoleList && $userRoles = $user->getRoles()) {
            $secretLoginRoleList = array_map('strtolower', $secretLoginRoleList);
            foreach ($userRoles as $userRole) {

                if (in_array(strtolower($userRole->getRoleId()), $secretLoginRoleList)) {

                    $code = $this->userCodeService->setCode4User($user, UserCodesEntity::TYPE_SECRET_LOGIN);
                    $this->mailService->secretLogin($user, $code);

                    $this->getFlashMessenger()
                        ->setNamespace($this::ERROR_NAME_SPACE)
                        ->addMessage('Please confirm your secret-login with your email');

                    $result = true;
                }
            }
        }

        return $result;
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    protected function isUserBlocked(UserInterface $user)
    {
        $userBlocked = $this->userBlockService->isUserBlocked($user);
        $result = false;

        if ($userBlocked) {
            $message = sprintf(
                'You are blocked because %s!, try it again @ %s',
                $userBlocked->getReason(),
                $userBlocked->getExpire()->format(
                    $this->getDateTimeFormatTime()
                )
            );
            $this->getFlashMessenger()->setNamespace($this::ERROR_NAME_SPACE)->addMessage($message);
            $result = true;
        }

        return $result;
    }

    /**
     * @param SmallUserInterface $user
     */
    protected function doLogin(SmallUserInterface $user)
    {
        parent::doLogin($user);
        $entityManager = $this->entityManager;
        /**
         * Set LoginHistory
         */
        $class = $this->collectionOptions->getEntityOptions()->getLoginHistory();
        /** @var \PServerCore\Entity\LoginHistory $loginHistory */
        $loginHistory = new $class();
        $loginHistory->setUser($user);
        $loginHistory->setIp($this->ipService->getIp());
        $entityManager->persist($loginHistory);
        $entityManager->flush();
    }

    /**
     * @param SmallUserInterface $user
     * @return bool
     */
    protected function handleInvalidLogin(SmallUserInterface $user)
    {
        $maxTries = $this->collectionOptions->getLoginOptions()->getExploit()['try'];

        if (!$maxTries) {
            return false;
        }

        $entityManager = $this->entityManager;
        /**
         * Set LoginHistory
         */
        $class = $this->collectionOptions->getEntityOptions()->getLoginFailed();
        /** @var \PServerCore\Entity\LoginFailed $loginFailed */
        $loginFailed = new $class();
        $loginFailed->setUsername($user->getUsername());
        $loginFailed->setIp($this->ipService->getIp());
        $entityManager->persist($loginFailed);
        $entityManager->flush();

        $time = $this->collectionOptions->getLoginOptions()->getExploit()['time'];

        /** @var \PServerCore\Entity\Repository\LoginFailed $repositoryLoginFailed */
        $repositoryLoginFailed = $entityManager->getRepository($class);

        if ($repositoryLoginFailed->getNumberOfFailLogin4Ip($this->ipService->getIp(), $time) >= $maxTries) {
            $class = $this->collectionOptions->getEntityOptions()->getIpBlock();
            /** @var \PServerCore\Entity\IpBlock $ipBlock */
            $ipBlock = new $class();
            $ipBlock->setExpire((new DateTime())->add(new DateInterval(sprintf('PT%sS', $time))));
            $ipBlock->setIp($this->ipService->getIp());
            $entityManager->persist($ipBlock);
            $entityManager->flush();
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function isIpAllowed()
    {
        $entityManager = $this->entityManager;
        /** @var \PServerCore\Entity\Repository\IPBlock $repositoryIPBlock */
        $repositoryIPBlock = $entityManager->getRepository($this->collectionOptions->getEntityOptions()->getIpBlock());
        $ipAllowed = $repositoryIPBlock->isIPAllowed($this->ipService->getIp());
        $result = true;

        if ($ipAllowed) {
            $message = sprintf('Your IP is blocked!, try it again @ %s', $ipAllowed->getExpire()->format('H:i:s'));
            $this->getFlashMessenger()->setNamespace($this::ERROR_NAME_SPACE)->addMessage($message);
            $result = false;
        }

        return $result;
    }

    /**
     * Login with a User
     *
     * @param UserInterface $user
     */
    public function doAuthentication(UserInterface $user)
    {
        /** @var \PServerCore\Entity\Repository\User $repository */
        $repository = $this->entityManager->getRepository($this->getUserEntityClassName());

        // fix if we have a proxy we don´t have a valid entity, so we have to clear before we can create a new select
        $username = $user->getUsername();
        $repository->clear();

        $userNew = $repository->getUser4UserName($username);

        $authService = $this->getAuthService();

        $authService->getStorage()->write($userNew);
    }

    /**
     * read from the config if system works for different pws @ web and in-game or with same
     * @return boolean
     */
    public function isSamePasswordOption()
    {
        return !(bool)$this->collectionOptions->getPasswordOptions()->isDifferentPasswords();
    }

    /**
     * @return boolean
     */
    public function isRegisterDynamicImport()
    {
        return (bool)$this->collectionOptions->getConfig()['register']['dynamic-import'];
    }

    /**
     * read from the config if system works for secret question
     * @return boolean
     */
    public function isSecretQuestionOption()
    {
        return $this->collectionOptions->getPasswordOptions()->isSecretQuestion();
    }

    /**
     * read from the config if system works with mail confirmation
     * @return boolean
     */
    public function isRegisterMailConfirmationOption()
    {
        return (bool)$this->collectionOptions->getRegisterOptions()->isMailConfirmation();
    }

    /**
     * @return string
     */
    public function getDateTimeFormatTime()
    {
        return $this->collectionOptions->getConfig()['general']['datetime']['format']['time'];
    }

    /**
     * @return FormInterface
     */
    public function getPasswordLostForm()
    {
        return $this->passwordLostForm;
    }

    /**
     * @return FormInterface
     */
    public function getPasswordForm()
    {
        return $this->passwordForm;
    }

    /**
     * @return FormInterface
     */
    public function getRegisterForm()
    {
        return $this->registerForm;
    }

    /**
     * @param UserInterface $user
     * @param $password
     * @return UserInterface
     */
    public function setNewPasswordAtUser(UserInterface $user, $password)
    {
        $entityManager = $this->entityManager;
        $user->setPassword($this->bCrypt($password));

        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    /**
     * We want to crypt a password =)
     *
     * @param $password
     *
     * @return string
     */
    protected function bCrypt($password)
    {
        if ($this->isSamePasswordOption()) {
            $result = $this->gameDataService->hashPassword($password);
        } else {
            $bCrypt = new Bcrypt();
            $result = $bCrypt->create($password);
        }

        return $result;
    }

    /**
     * @param UserInterface|SmallUserInterface $user
     * @return boolean
     */
    protected function handleAuth4UserLogin(SmallUserInterface $user)
    {
        if ($this->isRegisterDynamicImport()) {
            /** @var \PServerCore\Entity\Repository\User $userRepository */
            $userRepository = $this->entityManager->getRepository($this->collectionOptions->getEntityOptions()->getUser());
            if (!$userRepository->getUser4UserName($user->getUsername())) {
                /** @var UserInterface $backendUser */
                if ($backendUser = $this->gameDataService->getUser4Login($user)) {

                    if (!$backendUser->getCreateIp()) {
                        $backendUser->setCreateIp($this->ipService->getIp());
                    }

                    // we only save valid names
                    try {
                        if (!(new EmailAddress)->isValid($backendUser->getEmail())) {
                            $backendUser->setEmail('');
                        }
                    } catch (\Exception $e) {
                        $backendUser->setEmail('');
                    }

                    $backendUser->setPassword($this->bCrypt($user->getPassword()));
                    $entityManager = $this->entityManager;
                    $entityManager->persist($backendUser);
                    $entityManager->flush();

                    $this->setAvailableCountries4User($backendUser, $this->ipService->getIp());
                    $this->addDefaultRole($backendUser);

                    $this->doAuthentication($backendUser);

                    return true;
                }
            }
        }

        return parent::handleAuth4UserLogin($user);
    }

    /**
     * @return null|\PServerCore\Entity\Repository\SecretAnswer
     */
    protected function getEntityManagerAnswer()
    {
        return $this->entityManager->getRepository($this->collectionOptions->getEntityOptions()->getSecretAnswer());
    }

    /**
     * @param UserInterface $entity
     * @param string $plaintext
     * @return bool
     */
    public function hashPassword(UserInterface $entity, $plaintext)
    {
        if ($this->isSamePasswordOption()) {
            return $this->gameDataService->isPasswordSame($entity->getPassword(), $plaintext);
        }

        $bcrypt = new Bcrypt();

        return $bcrypt->verify($plaintext, $entity->getPassword());
    }
}