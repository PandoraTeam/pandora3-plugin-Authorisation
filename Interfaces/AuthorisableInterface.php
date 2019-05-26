<?php
namespace Pandora3\Plugins\Authorisation\Interfaces;

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