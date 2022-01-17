<?php

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Info;
use App\Model\User\Entity\User\User;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSignUpByEmail()
    {
        $user = (new UserBuilder)
			->byEmail(new Email('mytest@test.ru'))
			->withInfo(new Info('myName', 'mySurname'))
			->build();

        $this->assertEquals('mytest@test.ru', $user->getEmail());
        $this->assertEquals('hash', $user->getPasswordHash());
        $this->assertEquals('token', $user->getActivateToken());
        $this->assertEquals(User::STATUS_NO_ACTIVE, $user->getStatus());
		$this->assertEquals('myName', $user->getName());
		$this->assertEquals('mySurname', $user->getSurname());
    }
}
