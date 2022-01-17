<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\User\Service\PasswordHasher;
use App\Model\User\UseCase\ChangePassword\Form;
use App\Model\User\UseCase\ChangePassword\FormData;
use App\Model\User\UseCase\ChangePassword\Handler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{
	/**
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request): Response
	{
		return $this->render('app/profile/index.html.twig');
	}

	/**
	 * @param Request $request
	 * @param LoggerInterface $logger
	 * @param Security $security
	 * @param Handler $handler
	 * @return Response
	 */
	public function changePassword(Request $request,
	                               LoggerInterface $logger,
	                               Security $security,
	                               Handler $handler): Response
	{
		$formData = new FormData();
		$form = $this->createForm(Form::class, $formData);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			try {
				$user = $security->getUser();

				if(!PasswordHasher::verify($formData->oldpassword, $user->getPassword())){
					throw new \DomainException('Old password is not correct.');
				}

				if(!$formData->validate()){
					throw new \DomainException('Password and repeat password do not match.');
				}

				$handler->handle((int) $user->getId(), $formData->password);

				$this->addFlash('success', 'New password saved successfully.');
				return $this->redirectToRoute('app_profile');

			} catch (\DomainException|\InvalidArgumentException $e){
				$this->addFlash('error', $e->getMessage());
			} catch (\Exception $e){
				$this->addFlash('error', $e->getMessage());
				$logger->error($e->getMessage(), ['exception' => $e]);
			}
		}

		return $this->render('app/profile/change_password.html.twig', [
			'form' => $form->createView(),
		]);
	}
}