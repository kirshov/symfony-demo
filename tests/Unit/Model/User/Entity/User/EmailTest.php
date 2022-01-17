<?php

namespace App\Tests\Unit\Model\User\Entity\User;

use App\Model\User\Entity\User\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testSuccess()
    {
    	$email = 'TEST@TEST.ru';
        $this->assertEquals((new Email($email))->getEmail(), mb_strtolower($email));
    }

	public function testFailed()
	{
		$this->expectException(\InvalidArgumentException::class);
		new Email('novalid@email');
	}
}
