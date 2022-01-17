<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\ResetPassword\Request;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\ResetToken;
use App\Model\User\Entity\UserRepository;
use App\Model\User\Service\TokenGenerator;
use App\Service\Mailer;
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
	 * @var Mailer
	 */
	private $mailer;

	/**
	 * Handler constructor.
	 * @param UserRepository $userRepository
	 * @param EntityManagerInterface $em
	 * @param Mailer $mailer
	 */
	public function __construct(UserRepository $userRepository, EntityManagerInterface $em, Mailer $mailer)
	{
		$this->userRepository = $userRepository;
		$this->em = $em;
		$this->mailer = $mailer;
	}

	public function handle(Command $command): void
	{
		if(!$user = $this->userRepository->findByEmail(new Email($command->email))){
			throw new \DomainException('User not found');
		}
		$token = new ResetToken(TokenGenerator::generate(), (new \DateTimeImmutable())->modify('+1 day'));
		$user->requestResetPassword($token);
		$this->em->flush();

		$this->mailer->send($user->getEmail(), 'need activate', 'app/auth/email/reset_password.html.twig', [
			'username' => $user->getInfo()->getName(),
			'token' => $token->getToken(),
		]);
	}
}