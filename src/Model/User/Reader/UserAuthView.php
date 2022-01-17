<?php
declare(strict_types=1);

namespace App\Model\User\Reader;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Role;

class UserAuthView
{
	/** @var int  */
	public $id;

	/** @var string  */
	public $email;

	/** @var string  */
	public $password;

	/** @var string  */
	public $name;

	/** @var int  */
	public $role;

	/** @var int  */
	public $status;

	/**
	 * UserAuthView constructor.
	 * @param int $id
	 * @param Email $email
	 * @param string $password
	 * @param string $name
	 * @param Role $role
	 * @param int $status
	 */
	public function __construct(int $id, Email $email, string $password, string $name, Role $role, int $status)
	{
		$this->id = $id;
		$this->email = $email->getEmail();
		$this->password = $password;
		$this->name = $name;
		$this->role = $role->getName();
		$this->status = $status;
	}
}