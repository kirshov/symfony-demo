<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ResetToken
 * @package App\Model\User\Entity
 *
 * @ORM\Embeddable
 */
class ResetToken
{
	/**
	 * @var string
	 * @ORM\Column(name="token", nullable=true)
	 */
	private $token;

	/**
	 * @var string
	 * @ORM\Column(name="token_expire", type="datetime_immutable", nullable=true)
	 */
	private $expire;


	/**
	 * Token constructor.
	 * @param string $token
	 * @param \DateTimeImmutable $expire
	 */
	public function __construct(string $token, \DateTimeImmutable $expire)
	{
		$this->token = $token;
		$this->expire = $expire;
	}

	/**
	 * @return string
	 */
	public function getToken(): ?string
	{
		return $this->token;
	}

	/**
	 * @return bool
	 */
	public function isExpired(): bool
	{
		return $this->expire < new \DateTimeImmutable();
	}

	/**
	 * @return bool
	 */
	public function isEmpty(): bool
	{
		return empty($this->token);
	}
}