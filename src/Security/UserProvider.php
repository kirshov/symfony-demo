<?php
declare(strict_types=1);

namespace App\Security;

use App\Model\User\Reader\Reader;
use App\Model\User\Reader\UserAuthView;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
	/** @var Reader */
	private $reader;

	/**
	 * UserProvider constructor.
	 * @param Reader $reader
	 */
	public function __construct(Reader $reader) { $this->reader = $reader; }

	/**
	 * @param string $username
	 * @return UserInterface
	 */
	public function loadUserByUsername(string $username): UserInterface
	{
		$user = $this->loadUser($username);
		if(!$user){
			throw new UsernameNotFoundException('');
		}

		return self::identityByUser($user, $username);
	}

	/**
	 * @param UserInterface $user
	 * @return UserInterface
	 */
	public function refreshUser(UserInterface $user): UserInterface
	{
		if(!$user instanceof UserIdentity){
			throw new UnsupportedUserException('Invalid user class ' . get_class($user));
		}

		$newUser = $this->loadUser($user->getUsername());
		return self::identityByUser($newUser, $user->getUsername());
	}

	/**
	 * @param string $class
	 * @return bool
	 */
	public function supportsClass(string $class): bool
	{
		return $class === UserIdentity::class;
	}

	/**
	 * @param $username
	 * @return UserAuthView
	 */
	private function loadUser($username): UserAuthView
	{
		$user = $this->reader->findByAuth($username);
		if($user){
			return $user;
		}

		throw new UsernameNotFoundException('');
	}

	/**
	 * @param UserAuthView $user
	 * @param string $username
	 * @return UserIdentity
	 */
	private static function identityByUser(UserAuthView $user, string $username): UserIdentity
	{
		return new UserIdentity(
			$user->id,
			$user->email,
			$user->password ?: '',
			$user->name ?: $username,
			$user->role,
			$user->status
		);
	}
}