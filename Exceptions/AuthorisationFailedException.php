<?php
namespace Pandora3\Plugins\Authorisation\Exceptions;

use Exception;
use Pandora3\Core\Interfaces\Exceptions\ApplicationException;

/**
 * Class AuthorisationFailedException
 * @package Pandora3\Plugins\Authorisation\Exceptions
 */
class AuthorisationFailedException extends Exception implements ApplicationException { }