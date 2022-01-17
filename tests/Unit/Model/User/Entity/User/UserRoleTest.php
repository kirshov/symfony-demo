<?php

namespace App\Tests\Unit\Model\User\Entity\User;

use App\Model\User\Entity\User\Role;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    public function testSuccess()
    {
    	$mockRole = $this->createMock(Role::class);
    	$mockRole->method('isUser')->willReturn(true);
    	$mockRole->method('isAdmin')->willReturn(true);


    	$user = (new UserBuilder())->byEmail()->build();
    	$this->assertTrue($mockRole->isUser());

    	$newRole = Role::admin();
    	$user->changeRole($newRole);
    	$this->assertTrue($mockRole->isAdmin());
    }

}
