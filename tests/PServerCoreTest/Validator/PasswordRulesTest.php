<?php

namespace PServerCoreTest\Validator;

use PServerCore\Options\PasswordOptions;
use PServerCore\Validator\PasswordRules;
use PServerCoreTest\Util\TestBase;

class PasswordRulesTest extends TestBase
{
    protected $className = \PServerCore\Validator\PasswordRules::class;

    public function testIsValid()
    {
        $this->mockedConstructorArgList = [
            new PasswordOptions()
        ];

        $result = $this->getMethod('isValid')->invokeArgs($this->getClass(), ['test']);
        $this->assertTrue($result);
        $this->assertEmpty($this->getMethod('getMessages')->invokeArgs($this->getClass(), []));
    }

    public function testIsValidNumberCheck()
    {
        $this->mockedConstructorArgList = [
            (new PasswordOptions())->setContainsNumber(true)
        ];

        $result = $this->getMethod('isValid')->invokeArgs($this->getClass(), ['test']);
        $this->assertFalse($result);

        $message = $this->getMethod('getMessages')->invokeArgs($this->getClass(), []);
        $this->assertNotEmpty($message);
        $this->assertCount(1, $message);
        $this->assertArrayHasKey(PasswordRules::ERROR_NO_NUMBER, $message);
    }

    public function testIsValidNumberCheckSuccess()
    {
        $this->mockedConstructorArgList = [
            (new PasswordOptions())->setContainsNumber(true)
        ];

        $result = $this->getMethod('isValid')->invokeArgs($this->getClass(), ['t0est']);
        $this->assertTrue($result);
    }

    public function testIsValidLowerLetterCheck()
    {
        $this->mockedConstructorArgList = [
            (new PasswordOptions())->setContainsLowerLetter(true)
        ];

        $result = $this->getMethod('isValid')->invokeArgs($this->getClass(), ['TEST0']);
        $this->assertFalse($result);

        $message = $this->getMethod('getMessages')->invokeArgs($this->getClass(), []);
        $this->assertNotEmpty($message);
        $this->assertCount(1, $message);
        $this->assertArrayHasKey(PasswordRules::ERROR_NO_LOWER_CASE_LETTER, $message);
    }

    public function testIsValidLowerLetterCheckSuccess()
    {
        $this->mockedConstructorArgList = [
            (new PasswordOptions())->setContainsLowerLetter(true)
        ];

        $result = $this->getMethod('isValid')->invokeArgs($this->getClass(), ['test']);
        $this->assertTrue($result);
    }

    public function testIsValidUpperLetterCheck()
    {
        $this->mockedConstructorArgList = [
            (new PasswordOptions())->setContainsUpperLetter(true)
        ];

        $result = $this->getMethod('isValid')->invokeArgs($this->getClass(), ['test1']);
        $this->assertFalse($result);

        $message = $this->getMethod('getMessages')->invokeArgs($this->getClass(), []);
        $this->assertNotEmpty($message);
        $this->assertCount(1, $message);
        $this->assertArrayHasKey(PasswordRules::ERROR_NO_UPPER_CASE_LETTER, $message);
    }

    public function testIsValidUpperLetterCheckSuccess()
    {
        $this->mockedConstructorArgList = [
            (new PasswordOptions())->setContainsUpperLetter(true)
        ];

        $result = $this->getMethod('isValid')->invokeArgs($this->getClass(), ['TEST1']);
        $this->assertTrue($result);
    }

    public function testIsValidSpecialCharacterCheck()
    {
        $this->mockedConstructorArgList = [
            (new PasswordOptions())->setContainsSpecialChar(true)
        ];

        $result = $this->getMethod('isValid')->invokeArgs($this->getClass(), ['test1']);
        $this->assertFalse($result);

        $message = $this->getMethod('getMessages')->invokeArgs($this->getClass(), []);
        $this->assertNotEmpty($message);
        $this->assertCount(1, $message);
        $this->assertArrayHasKey(PasswordRules::ERROR_NO_SPECIAL_CHAR, $message);
    }

    public function setContainsSpecialChar()
    {
        $this->mockedConstructorArgList = [
            (new PasswordOptions())->setContainsUpperLetter(true)
        ];

        $result = $this->getMethod('isValid')->invokeArgs($this->getClass(), ['TES+T1']);
        $this->assertTrue($result);
    }


}
