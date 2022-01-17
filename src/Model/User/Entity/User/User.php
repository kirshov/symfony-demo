<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class User
{
	const STATUS_NO_ACTIVE = 0;
	const STATUS_ACTIVE = 1;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="message_seq", initialValue=1, allocationSize=100)
	 */
	private $id;

	/**
	 * @var string
	 * @ORM\Column(type="datetime_immutable")
	 */
	private $create_time;

	/**
	 * @var Email
	 * @ORM\Column(type="user_user_email", nullable=true)
	 */
	private $email;

	/**
	 * @var Info
	 * @ORM\Embedded(class="Info", columnPrefix=false)
	 */
	private $info;

	/**
	 * Password hash
	 * @var $password
	 * @ORM\Column
	 */
	private $password;

	/**
	 * @var int
	 * @ORM\Column(type="smallint")
	 */
	private $status;

	/**
	 * @var string
	 * @ORM\Column(name="activation_token", nullable=true)
	 */
	private $activationToken;

	/**
	 * @var ResetToken
	 * @ORM\Embedded(class="ResetToken", columnPrefix="reset_")
	 */
	private $resetToken;

	/**
	 * @var string
	 * @ORM\Column(type="user_user_role", length=20)
	 */
	private $role;

	/**
	 * User constructor.
	 * @throws \Exception
	 */
	protected function __construct()
	{
		$this->create_time = new \DateTimeImmutable();
		$this->status = self::STATUS_NO_ACTIVE;
		$this->role = Role::user();
	}

	/**
	 * @param Email $email
	 * @param Info $info
	 * @param string $hash
	 * @param string $token
	 * @return static
	 */
	public static function signUpByEmail(Email $email, Info $info, string $hash, string $token) : self {
		$user = new self();
		$user->email = $email;
		$user->info = $info;
		$user->password = $hash;
		$user->activationToken = $token;

		return $user;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->info->getName();
	}

	/**
	 * @return string
	 */
	public function getSurname(): string
	{
		return $this->info->getSurname();
	}

	/**
	 * @return Info
	 */
	public function getInfo(): Info
	{
		return $this->info;
	}

	/**
	 * @return mixed
	 */
	public function getEmail(): string
	{
		return $this->email->getEmail();
	}

	/**
	 * @return string
	 */
	public function getPasswordHash(): string
	{
		return $this->password;
	}

	/**
	 * @return int
	 */
	public function getStatus(): int
	{
		return $this->status;
	}

	/**
	 * @return bool
	 */
	public function isActive(): bool
	{
		return $this->status === self::STATUS_ACTIVE;
	}

	/**
	 *
	 */
	public function activate(): void
	{
		if($this->isActive()){
			throw new \DomainException('User already active');
		}
		$this->status = self::STATUS_ACTIVE;
		$this->activationToken = '';
	}

	/**
	 * @return string
	 */
	public function getActivateToken(): string
	{
		return $this->activationToken;
	}

	/**
	 * @param Role $role
	 */
	public function changeRole(Role $role): void
	{
		$this->role = $role;
	}

	/**
	 * @return Role
	 */
	public function getRole(): Role
	{
		return $this->role;
	}

	/**
	 * @param ResetToken $token
	 */
	public function requestResetPassword(ResetToken $token): void
	{
		if (!$this->isActive()) {
			throw new \DomainException('User is not active.');
		}

		if ($this->resetToken && !$this->resetToken->isExpired()) {
			throw new \DomainException('Resetting is already requested.');
		}

		$this->resetToken = $token;
	}

	/**
	 * @param $hash
	 */
	public function resetPassword($hash): void
	{
		if (empty($hash)) {
			throw new \DomainException('Password is empty.');
		}

		if (!$this->resetToken) {
			throw new \DomainException('Resetting is not requested.');
		}

		if ($this->resetToken->isExpired()) {
			throw new \DomainException('Resetting  token is expected.');
		}

		$this->resetToken = null;

		$this->password = $hash;
	}

	/**
	 * @return ResetToken
	 */
	public function getResetToken(): ?ResetToken
	{
		return $this->resetToken;
	}

	/**
	 * @param $hash
	 */
	public function setPassword($hash): void
	{
		$this->password = $hash;
	}

	/**
	 * @ORM\PostLoad()
	 */
	public function checkEmbeds(): void
	{
		if (empty($this->resetToken->getToken())) {
			$this->resetToken = null;
		}
	}
}