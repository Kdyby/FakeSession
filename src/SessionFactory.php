<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\FakeSession;

use Nette;



class SessionFactory extends Nette\Object
{

	/**
	 * @var Nette\DI\Container
	 */
	private $original;



	public function __construct(Nette\Http\Session $original)
	{
		$this->original = $original;
	}



	public function create($enabled)
	{
		if (!$enabled) {
			return $this->original;
		}

		return new Session();
	}

}
