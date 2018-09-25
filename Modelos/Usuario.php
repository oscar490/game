
<?php

class Usuario {

	public $email;
	public $password;
	public $password_repeat;

	public function __construct()
	{
		$this->setEmail(null);
		$this->setPassword(null);
	}

	private function setEmail($email)
	{
		$this->email = $email;
	}

	private function setPassword($password)
	{
		$this->password = $password;
	}
	
}