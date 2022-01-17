<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class EmailType extends StringType
{
	public const NAME = 'user_user_email';

	public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
	{
		return $value instanceof Email ? $value->getEmail() : null;
	}

	public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
	{
		return !empty($value) ? new Email($value) : null;
	}

	public function getName(): string
	{
		return self::NAME;
	}

	public function requiresSQLCommentHint(AbstractPlatform $platform): bool
	{
		return true;
	}
}