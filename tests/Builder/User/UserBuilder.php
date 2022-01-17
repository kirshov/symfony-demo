<?php
declare(strict_types=1);

namespace App\Tests\Builder\User;


use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Info;
use App\Model\User\Entity\User\User;
use PHPUnit\Runner\Exception;

class UserBuilder
{
	/** @var Email */
	private $email;

	/** @var Info */
	private $info;

	/** @var string */
	private $create_time;

	/** @var string */
	private $hash;

	/** @var string */
	private $token;

	/** @var bool */
	private $active;

	/**
	 * UserBuilder constructor.
	 */
	public function __construct() {
		$this->create_time = new \DateTimeImmutable();
		$this->info = new Info('name', 'surname');
	}

	public function byEmail(Email $email = null, string $hash = null, $token = null) : self
	{
		$this->email = $email ?? new Email('test@test.ru');
		$this->hash = $hash ?? 'hash';
		$this->token = $token ?? 'token';

		return $this;
	}

	public function withInfo(Info $info): self
	{
		$this->info = $info;

		return $this;
	}

	public function activated(): self{
		$this->active = true;

		return $this;
	}

	public function build() : User
	{
		$user = null;
		if($this->email){
			$user = User::signUpByEmail(
				$this->email,
				$this->info,
				$this->hash,
				$this->token,
			);
		}

		if($user === null){
			throw new Exception('User is empty');
		}

		if($this->active){
			$user->activate();
		}

		return $user;
	}
}