<?php


namespace App\Service;


use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{
	/** @var MailerInterface */
	protected $mailer;

	/** @var LoggerInterface */
	protected $logger;

	/**
	 * Mailer constructor.
	 * @param MailerInterface $mailer
	 * @param LoggerInterface $logger
	 */
	public function __construct(MailerInterface $mailer, LoggerInterface $logger) {
		$this->mailer = $mailer;
		$this->logger = $logger;
	}

	/**
	 * @param string $to
	 * @param string $subject
	 * @param string $template
	 * @param array $params
	 * @return bool
	 */
	public function send(string $to, string $subject, string $template, array $params = []): bool
	{
		$email = (new TemplatedEmail())
			->from('noreply@'.$_SERVER['SERVER_NAME'])
			->to(new Address($to))
			->subject($subject)
			->htmlTemplate($template)
			->context($params);

		try {
			$this->mailer->send($email);
			return true;
		} catch (TransportExceptionInterface $e) {
			$this->logger->error($e->getMessage(), ['exception' => $e]);
			return false;
		}
	}
}