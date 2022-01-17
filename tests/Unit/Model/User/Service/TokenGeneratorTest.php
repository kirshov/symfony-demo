<?php

namespace App\Tests\Unit\Model\User\Service;

use App\Model\User\Service\TokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase
{
    public function testSuccess()
    {
        $this->assertIsString(TokenGenerator::generate());
    }
}
