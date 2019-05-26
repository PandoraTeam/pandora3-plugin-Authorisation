<?php
namespace Pandora3\Plugins\Authorisation\Interfaces;

interface UserProviderInterface {

	/**
	 * @param mixed|null $id
	 * @return AuthorisableInterface|null
	 */
	function getUserById($id): ?AuthorisableInterface;

	/**
	 * @param string $login
	 * @return AuthorisableInterface|null
	 */
	function getUserByLogin(string $login): ?AuthorisableInterface;

	/**
	 * @return mixed|null
	 */
	function getGuestUser();

}