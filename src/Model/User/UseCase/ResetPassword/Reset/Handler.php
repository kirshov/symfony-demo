<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\ResetPassword\Reset;

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
	 * @param Command $command
	 */
	public function handle(Command $command): void
	{
		if(!$user = $this->userRepository->findByResetToken($command->token)){
			throw new \DomainException('User not found.');
		}

		$user->resetPassword(PasswordHasher::generate($command->password));
		$this->em->flush();
	}
}