<?php
namespace Pandora3\Plugins\Authorisation\Middlewares;

use Closure;
use Pandora3\Core\Interfaces\RequestHandlerInterface;
use Pandora3\Core\Interfaces\RequestInterface;
use Pandora3\Core\Interfaces\ResponseInterface;
use Pandora3\Core\Middleware\Interfaces\MiddlewareInterface;
use Pandora3\Core\Router\RequestHandler;
use Pandora3\Plugins\Authorisation\Authorisation;

/**
 * Class AuthorisedMiddleware
 * @package Pandora3\Plugins\Authorisation\Middlewares
 */
class AuthorisedMiddleware implements MiddlewareInterface {
	
	/** @var RequestHandlerInterface $unauthorisedHandler */
	protected $unauthorisedHandler;
	
	/** @var Authorisation $authorisation */
	protected $authorisation;
	
	/**
	 * @param RequestHandlerInterface|Closure $unauthorisedHandler
	 * @param Authorisation $authorisation
	 */
	public function __construct($unauthorisedHandler, Authorisation $authorisation) {
		if ($unauthorisedHandler instanceof Closure) {
			$unauthorisedHandler = new RequestHandler($unauthorisedHandler);
		}
		$this->unauthorisedHandler = $unauthorisedHandler;
		$this->authorisation = $authorisation;
	}
	
	/**
	 * {@inheritdoc}
	 */
	function process(RequestInterface $request, array $arguments, RequestHandlerInterface $handler): ResponseInterface {
		if (!$this->authorisation->isAuthorised()) {
			$handler = $this->unauthorisedHandler;
		}
		return $handler->handle($request, $arguments);
	}
	
}