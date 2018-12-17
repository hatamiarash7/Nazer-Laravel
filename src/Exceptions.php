<?php

namespace Hatamiarash7\Nazer;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hatamiarash7\Nazer\Decorator
 */
class Exceptions extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'exceptions';
	}
}