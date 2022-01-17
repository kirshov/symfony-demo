<?php


namespace App\Tests\Unit\Model\User\Entity\User\ResetPassword;


use App\Model\User\Entity\User\ResetToken;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
	public function testSuccess(){
		$newToken = 'new_token';

		$user = (new UserBuilder())->byEmail()->activated()->build();

		$user->requestResetPassword($this->getTokenMock(), (new \DateTimeImmutable())->modify('+1 day'));
		$this->assertEquals($user->getResetToken()->getToken(), $newToken);
	}

	public function testNotActiveUser(){
		$user = (new UserBuilder())->byEmail()->build();

		$this->expectException(\DomainException::class);
		$user->requestResetPassword($this->getTokenMock(), (new \DateTimeImmutable())->modify('+1 day'));
	}

	public function testAlready(){
		$user = (new UserBuilder())->byEmail()->activated()->build();

		$user->requestResetPassword($this->getTokenMock(), (new \DateTimeImmutable())->modify('+1 day'));

		$this->expectException(\DomainException::class);
		$user->requestResetPassword($this->getTokenMock(), (new \DateTimeImmutable())->modify('+1 day'));
	}


	public function testExpiredToken(){
		$user = (new UserBuilder())->byEmail()->activated()->build();

		$user->requestResetPassword($this->getTokenMock(), (new \DateTimeImmutable())->modify('+1 day'));

		$this->expectException(\DomainException::class);
		$user->requestResetPassword($this->getTokenMock(), (new \DateTimeImmutable())->modify('+1 hour'));
	}

	/**
	 * @return ResetToken
	 */
	protected function getTokenMock():ResetToken {
		$tokenMock = $this->createMock(ResetToken::class);
		$tokenMock->method('getToken')
			->willReturn('new_token');
		return $tokenMock;
	}
}