<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

class Role
{
	public const ROLE_USER = 'ROLE_USER';
	public const ROLE_ADMIN = 'ROLE_ADMIN';

	/** @var string */
	private $name;

	/**
	 * Role constructor.
	 * @param string $name
	 */
	public function __construct(string $name)
	{
		if(!in_array($name, [self::ROLE_USER, self::ROLE_ADMIN])){
			throw new \InvalidArgumentException('Role is not valid');
		}

		$this->name = $name;
	}

	/**
	 * @return static
	 */
	public static function user(): self
	{
		return new self(self::ROLE_USER);
	}

	/*
	 *
	 */
	public static function admin(): self
	{
		return new self(self::ROLE_ADMIN);
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return bool
	 */
	public function isAdmin(): bool
	{
		return $this->name === self::ROLE_ADMIN;
	}

	/**
	 * @return bool
	 */
	public function isUser(): bool
	{
		return $this->name === self::ROLE_USER;
	}
}