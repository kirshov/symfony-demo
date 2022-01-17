<?php

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class ActivateTest extends TestCase
{
    public function testSuccess()
    {
		$user = (new UserBuilder)->byEmail()->build();
		$user->activate();

		$this->assertTrue($user->isActive());
		$this->assertEmpty($user->getActivateToken());
    }

    public function testAlready()
	{
		$user = (new UserBuilder)->byEmail()->activated()->build();
		$this->expectException(\DomainException::class);
		$user->activate();
	}
}
