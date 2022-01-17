<?php
declare(strict_types=1);

namespace App\Model\User\UseCase\ChangePassword;


class FormData
{
	/** @var string */
	public $oldpassword;

	/** @var string */
	public $password;

	/** @var string */
	public $repassword;

	/**
	 * @return bool
	 */
	public function validate(): bool
	{
		return !empty($this->password) && $this->password == $this->repassword;
	}
}