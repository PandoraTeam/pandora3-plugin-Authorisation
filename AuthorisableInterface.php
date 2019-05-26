<?php
namespace Pandora3\Plugins\Authorisation;

interface AuthorisableInterface {

	/**
	 * @return mixed
	 */
	function getAuthorisationId();

	/**
	 * @param string $password
	 * @return bool
	 */
	function checkPassword(string $password): bool;

}