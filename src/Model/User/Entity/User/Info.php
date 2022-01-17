<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Info
 * @package App\Model\User\Entity
 *
 * @ORM\Embeddable
 */
class Info
{
	/**
	 * @var string
	 * @ORM\Column(length=32, nullable=true)
	 */
	private $name;

	/**
	 * @var string
	 * @ORM\Column(length=32, nullable=true)
	 */
	private $surname;

	/**
	 * Email constructor.
	 * @param string $name
	 * @param string|null $surname
	 */
	public function __construct(?string $name, ?string $surname)
	{
		$this->name = $name;
		$this->surname = $surname;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getSurname(): string
	{
		return $this->surname;
	}
}