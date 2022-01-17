<?php

namespace App\Tests\Unit\Model\User\Entity\User;

use App\Model\User\Entity\User\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testSuccessUser()
    {
    	$role = Role::user();
        $this->assertTrue($role->isUser());
        $this->assertFalse($role->isAdmin());
    }

	public function testSuccessAdmin()
	{
		$role = Role::admin();
		$this->assertTrue($role->isAdmin());
    }

	public function testFailed()
	{
		$this->expectException(\InvalidArgumentException::class);
		new Role('unknown');
	}
}
