<?php

namespace App\Tests\Unit\Model\User\Entity\User;

use App\Model\User\UseCase\ChangePassword\FormData;
use PHPUnit\Framework\TestCase;

class ChangePasswordTest extends TestCase
{
    public function testSuccess(): void
    {
	    $formData = new FormData();
	    $formData->password = 'new_password';
	    $formData->repassword = 'new_password';

        $this->assertTrue($formData->validate());
    }

	public function testFailed(): void
	{
		$formData = new FormData();
		$formData->password = 'bad_password';
		$formData->repassword = 'new_password';

		$this->assertFalse($formData->validate());
	}
}
