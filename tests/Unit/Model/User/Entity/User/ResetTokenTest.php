<?php

namespace App\Tests\Unit\Model\User\Entity\User;

use App\Model\User\Entity\User\ResetToken;
use PHPUnit\Framework\TestCase;

class ResetTokenTest extends TestCase
{
    public function testSuccess()
    {
    	$tokenValue = 'token';
    	$expires = (new \DateTimeImmutable())->modify('+1 day');
		$token = new ResetToken($tokenValue, $expires);

        $this->assertEquals($token->getToken(), $tokenValue);
        $this->assertTrue(!$token->isExpired());
    }


	public function testEmpty(){
		$expires = (new \DateTimeImmutable())->modify('+1 day');
		$token = new ResetToken('', $expires);

		$this->assertTrue($token->isEmpty());
	}
}
