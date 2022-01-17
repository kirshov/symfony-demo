<?php
declare(strict_types=1);

namespace App\Controller\Auth;


use App\Model\User\Entity\UserRepository;
use App\Model\User\UseCase\ResetPassword\Request\Command;
use App\Model\User\UseCase\ResetPassword\Request\Form as RequestForm;
use App\Model\User\UseCase\ResetPassword\Request\Handler as RequestHandler;
use App\Model\User\UseCase\ResetPassword\Reset\Command as ResetCommand;
use App\Model\User\UseCase\ResetPassword\Reset\Form as ResetForm;
use App\Model\User\UseCase\ResetPassword\Reset\FormData;
use App\Model\User\UseCase\ResetPassword\Reset\Handler as ResetHandler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResetController extends AbstractController
{
	/**
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * ResetController constructor.
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}


	/**
	 * @param Request $request
	 * @param RequestHandler $handler
	 * @return Response
	 */
	public function request(Request $request, RequestHandler $handler): Response
	{
		$command = new Command();
		$form = $this->createForm(RequestForm::class, $command);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			try {
				$handler->handle($command);
				$this->addFlash('success', 'Further instructions have been sent to email.');

				return $this->redirectToRoute('forgot_password');
			} catch (\DomainException|\InvalidArgumentException $e){
				$this->addFlash('error', $e->getMessage());
			} catch (\Exception $e){
				$this->addFlash('error', $e->getMessage());
				$this->logger->error($e->getMessage(), ['exception' => $e]);
			}
		}

		return $this->render('app/auth/reset_password/forgot.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @param Request $request
	 * @param ResetHandler $handler
	 * @param UserRepository $userRepository
	 * @return Response
	 */
	public function reset(Request $request, ResetHandler $handler, UserRepository $userRepository): Response
	{
		$token = $request->get('token');
		$formData = new FormData();
		$form = $this->createForm(ResetForm::class, $formData);
		$form->handleRequest($request);

		try {
			if(!$user = $userRepository->findByResetToken($token)){
				throw new \DomainException('User not found.');
			}

			if($form->isSubmitted() && $form->isValid()){
				if(!$formData->validate()){
					throw new \InvalidArgumentException('The entered passwords do not match.');
				}

				$command = new ResetCommand($token, $formData->password);
				$handler->handle($command);
				$this->addFlash('success', 'New password saved successfully.');

				return $this->redirectToRoute('app_login');
			}
		} catch (\DomainException|\InvalidArgumentException $e){
			$this->addFlash('error', $e->getMessage());
		} catch (\Exception $e){
			$this->addFlash('error', $e->getMessage());
			$this->logger->error($e->getMessage(), ['exception' => $e]);
		}

		return $this->render('app/auth/reset_password/reset.html.twig', [
			'form' => $form->createView(),
		]);
	}
}