<?php

namespace App\Tests\Unit\Model\User\Entity\User\ResetPassword;

use App\Model\User\Entity\User\ResetToken;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class ResetTest extends TestCase
{
    public function testSuccess(): void
    {
    	$tokenMock = $this->getTokenMock();
    	$user = (new UserBuilder)->byEmail()->activated()->build();
    	$user->requestResetPassword($tokenMock);
    	$user->resetPassword('hash');

    	$this->assertEquals('hash', $user->getPasswordHash());
    }

	public function testNotRequested(): void
	{
		$user = (new UserBuilder)->byEmail()->activated()->build();

		$this->expectExceptionMessage('Resetting is not requested.');
		$user->resetPassword('hash');
	}

	public function testExpiredToken(): void
	{
		$tokenMock = $this->getTokenMock(true);
		$user = (new UserBuilder)->byEmail()->activated()->build();
		$user->requestResetPassword($tokenMock);

		$this->expectExceptionMessage('Resetting  token is expected.');
		$user->resetPassword('hash');
	}

	public function testEmptyHash(): void
	{
		$user = (new UserBuilder)->byEmail()->activated()->build();

		$this->expectExceptionMessage('Password is empty.');
		$user->resetPassword('');
	}

	public function testClearToken(): void
	{
		$tokenMock = $this->getTokenMock();
		$user = (new UserBuilder)->byEmail()->activated()->build();
		$user->requestResetPassword($tokenMock);
		$user->resetPassword('hash');

		$this->assertNull($user->getResetToken());
	}

	/**
	 * @param bool $expired
	 * @return ResetToken
	 */
	protected function getTokenMock($expired = false):ResetToken
	{
		$tokenMock = $this->createMock(ResetToken::class);
		$tokenMock
			->expects($this->any())
			->method('getToken')->willReturn('new_token');

		$tokenMock
			->expects($this->any())
			->method('isExpired')->willReturn($expired);
		return $tokenMock;
	}
}
