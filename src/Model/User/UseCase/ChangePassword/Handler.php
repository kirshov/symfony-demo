<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\ChangePassword;

use App\Model\User\Entity\User\Token;
use App\Model\User\Entity\UserRepository;
use App\Model\User\Service\PasswordHasher;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
	/**
	 * @var UserRepository
	 */
	private $userRepository;
	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	/**
	 * Handler constructor.
	 * @param UserRepository $userRepository
	 * @param EntityManagerInterface $em
	 */
	public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
	{
		$this->userRepository = $userRepository;
		$this->em = $em;
	}

	/**
	 * @param int $userId
	 * @param string $password
	 */
	public function handle(int $userId, string $password): void
	{
		if(!$user = $this->userRepository->findById($userId)){
			throw new \DomainException('User not found.');
		}

		$user->setPassword(PasswordHasher::generate($password));
		$this->em->flush();
	}
}