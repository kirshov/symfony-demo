<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Info;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\UserRepository;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\TokenGenerator;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
	/** @var EntityManagerInterface */
	private $em;

	/** @var UserRepository */
	private $userRepository;

	/** @var Mailer */
	private $mailer;

	/**
	 * RequestCommand constructor.
	 * @param EntityManagerInterface $em
	 * @param UserRepository $userRepository
	 * @param Mailer $mailer
	 */
	public function __construct(
		EntityManagerInterface $em,
		UserRepository $userRepository,
		Mailer $mailer
	)
	{
		$this->em = $em;
		$this->userRepository = $userRepository;
		$this->mailer = $mailer;
	}

	/**
	 * @param Command $command
	 */
	public function handle(Command $command): void
	{
		$email = new Email($command->email);

		if($this->userRepository->hasByEmail($email)){
			throw new \DomainException('User already exist');
		}

		$token = TokenGenerator::generate();
		$user = User::signUpByEmail(
			$email,
			new Info($command->firstName, $command->lastName),
			PasswordHasher::generate($command->password),
			$token
		);

		//сразу активируем
		/*$this->mailer->send($email->getEmail(), 'need activate', 'app/auth/email/activate_account.html.twig', [
			'username' => $command->firstName,
			'token' => $token,
		]);*/

		$user->activate();

		$this->em->persist($user);
		$this->em->flush();
	}
}