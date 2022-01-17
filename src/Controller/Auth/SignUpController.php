<?php
declare(strict_types=1);

namespace App\Controller\Auth;

use App\Model\User\UseCase\SignUp;
use App\Model\User\UseCase\SignUp\Activate\Handler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SignUpController extends AbstractController
{
	/** @var LoggerInterface */
	protected $logger;

	/**
	 * SignUpController constructor.
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger) {
		$this->logger = $logger;
	}

	/**
	 * @param Request $request
	 * @param SignUp\Request\Handler $handler
	 * @return Response
	 */
	public function request(Request $request, SignUp\Request\Handler $handler): Response
	{
		$command = new SignUp\Request\Command();
		$form = $this->createForm(SignUp\Request\Form::class, $command);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			try{
				$handler->handle($command);
				$this->addFlash('success', 'Registration is done.');
			} catch (\DomainException|\InvalidArgumentException $e) {
				$this->addFlash('error', $e->getMessage());
			} catch (\Exception $e) {
				$this->addFlash('error', $e->getMessage());
				$this->logger->error($e->getMessage(), ['exception' => $e]);
			}
		}

		return $this->render('app/auth/signup.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @param Request $request
	 * @param Handler $handler
	 * @return Response
	 */
	public function activate(Request $request, Handler $handler): Response
	{
		$token = $request->get('token');
		try {
			if(empty($token)){
				throw new \InvalidArgumentException('Invalid token');
			}

			$command = new SignUp\Activate\Command();
			$command->token = $token;
			$handler->handle($command);

			$this->addFlash('success', 'Account activated success');

			return $this->redirect($this->generateUrl('app_login'));
		} catch (\DomainException|\InvalidArgumentException $e){
			$this->addFlash('error', $e->getMessage());
		} catch (\Exception $e){
			$this->addFlash('error', $e->getMessage());
			$this->logger->error($e, ['exception' => $e]);
		}

		return $this->render('empty.html.twig');
	}
}