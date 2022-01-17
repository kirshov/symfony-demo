<?php


namespace App\Model\User\Service;


use Ramsey\Uuid\Uuid;

class TokenGenerator
{
	/**
	 * @return string
	 */
	public static function generate() : string{
		return Uuid::uuid4()->toString();
	}
}