<?php

namespace PServerCoreTest\Validator;

use PServerCoreTest\Util\TestBase;

class StriposExistsTest extends TestBase
{
    /** @var string */
    protected $className = \PServerCore\Validator\StriposExists::class;

    public function testIsValid()
    {
        $config = [
            'blacklisted' => [
                'email' => [
                    'foo.bar'
                ],
            ],
        ];

        $this->mockedConstructorArgList = [
            $config,
            \PServerCore\Validator\StriposExists::TYPE_EMAIL
        ];
        $class = $this->getClass();

        $this->assertTrue($class->isValid('foo@bar.sucks'));
        $this->assertEmpty($class->getMessages());
    }

    public function testIsValidFalse()
    {
        $config = [
            'blacklisted' => [
                'email' => [
                    'foo.bar'
                ],
            ],
        ];

        $this->mockedConstructorArgList = [
            $config,
            \PServerCore\Validator\StriposExists::TYPE_EMAIL
        ];
        $class = $this->getClass();

        $this->assertFalse($class->isValid('bar.sucks@foo.bar'));
        $this->assertNotEmpty($class->getMessages());
    }

}
