<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Activate;

use App\Model\User\Entity\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

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
	 * @throws ORMException
	 * @throws OptimisticLockException
	 */
	public function handle(Command $command): void
	{
		if(!$user = $this->userRepository->findByActivationToken($command->token)){
			throw new \DomainException('Incorrect token');
		}

		$user->activate();
		$this->em->flush();
	}
}