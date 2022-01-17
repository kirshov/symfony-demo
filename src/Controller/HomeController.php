<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\User\Reader\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
	public function index(Reader $reader): Response
	{
		return $this->render('home.html.twig');
	}
}