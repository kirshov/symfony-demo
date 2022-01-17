<?php
declare(strict_types=1);

namespace App\Model\User\Reader;

use App\Model\User\Entity\User\User;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class Reader
{
	/** @var Connection */
	private Connection $connection;

	/** @var EntityManagerInterface */
	private EntityManagerInterface $entityManager;

	/**
	 * Reader constructor.
	 * @param $connection
	 * @param $entityManager
	 */
	public function __construct(Connection $connection, EntityManagerInterface $entityManager)
	{
		$this->connection = $connection;
		$this->entityManager = $entityManager;
	}

	/**
	 * @param string $email
	 * @return UserAuthView|null
	 * @throws \Doctrine\ORM\NoResultException
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function findByAuth(string $email): ?UserAuthView
	{

		return $this->entityManager->createQueryBuilder()
			->select(sprintf(
				'NEW %s(user.id, user.email, user.password, user.info.name, user.role, user.status)',
				UserAuthView::class
			))
			->from(User::class, 'user')
			->where('user.email = :email')
			->setParameter(':email', $email)
			->getQuery()
			->getSingleResult();
	}
}