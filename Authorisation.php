<?php
namespace Pandora3\Plugins\Authorisation;

use Pandora3\Core\Interfaces\SessionInterface;
use Pandora3\Plugins\Authorisation\Exceptions\AuthorisationFailedException;
use Pandora3\Plugins\Authorisation\Interfaces\AuthorisableInterface;
use Pandora3\Plugins\Authorisation\Interfaces\UserProviderInterface;

/**
 * Class Authorisation
 * @package Pandora3\Plugins\Authorisation
 */
class Authorisation {

	/** @var SessionInterface $session */
	protected $session;

	/** @var UserProviderInterface $userProvider */
	protected $userProvider;
	
	/** @var AuthorisableInterface $user */
	protected $user;
	
	/** @var int|null $userId */
	protected $userId;

	/**
	 * @param SessionInterface $session
	 * @param UserProviderInterface|null $userProvider
	 */
	public function __construct(SessionInterface $session, ?UserProviderInterface $userProvider = null) {
		$this->session = $session;
		$this->userProvider = $userProvider;
	}

	public function authorise(AuthorisableInterface $user): void {
		$this->user = $user;
		$this->userId = $user->getAuthorisationId();
		$this->session->set('userId', $this->userId);
	}
	
	public function unAuthorise(): void {
		$this->user = null;
		$this->userId = 0;
		$this->session->clear('userId');
	}

	/**
	 * @param string $login
	 * @param string $password
	 * @return AuthorisableInterface|null
	 */
	public function getByCredentials(string $login, string $password): ?AuthorisableInterface {
		$user = $this->userProvider->getUserByLogin($login);
		if ($user && $user->checkPassword($password)) {
			return $user;
		}
		return null;
	}

	/**
	 * @param string $login
	 * @param string $password
	 * @return AuthorisableInterface|null
	 * @throws AuthorisationFailedException
	 */
	public function authoriseByCredentials(string $login, string $password): ?AuthorisableInterface {
		$user = $this->getByCredentials($login, $password);
		if (!$user) {
			throw new AuthorisationFailedException('Неверный логин или пароль'); // Wrong login or password
		}
		$this->authorise($user);
		return $user;
	}
	
	/**
	 * @return bool
	 */
	public function isAuthorised(): bool {
		$user = $this->getUser();
		$guest = $this->userProvider->getGuestUser();
		return $guest ? ($user !== $guest) : !is_null($user);
	}

	/**
	 * @return AuthorisableInterface|null
	 */
	public function getUser(): ?AuthorisableInterface {
		if ($this->userProvider && is_null($this->userId)) {
			$this->userId = $this->session->get('userId') ?? 0;
			if ($this->userId) {
				$this->user = $this->userProvider->getUserById($this->userId) ?? $this->userProvider->getGuestUser();
			}
		}
		return $this->user;
	}

}