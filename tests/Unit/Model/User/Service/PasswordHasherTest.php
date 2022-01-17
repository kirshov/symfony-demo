<?php

namespace App\Tests\Unit\Model\User\Service;

use App\Model\User\Service\PasswordHasher;
use PHPUnit\Framework\TestCase;

class PasswordHasherTest extends TestCase
{
    public function testGenerate()
    {
    	$password = 'password';
    	$hash = PasswordHasher::generate($password);
        $this->assertNotFalse($hash);
        $this->assertIsString($hash);
    }

	public function testVerify()
	{
		$password = 'password';
		$hash = PasswordHasher::generate($password);

		$this->assertTrue(PasswordHasher::verify($password, $hash));
		$this->assertFalse(PasswordHasher::verify($password, $hash.$hash));
	}
}
