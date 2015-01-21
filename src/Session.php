<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\FakeSession;

use Kdyby;
use Nette;
use Nette\Http\ISessionStorage;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class Session extends Nette\Http\Session
{

	/**
	 * @var array|SessionSection[]
	 */
	private $sections = [];



	public function __construct()
	{
		// no dependencies
	}



	public function start()
	{
		// nope
	}



	public function isStarted()
	{
		return FALSE;
	}



	public function close()
	{
		// nope
	}



	public function destroy()
	{
		// nope
	}



	public function exists()
	{
		return FALSE;
	}



	public function regenerateId()
	{
		// nope
	}



	public function getId()
	{
		return NULL;
	}



	public function getSection($section, $class = 'Kdyby\FakeSession\SessionSection')
	{
		if (isset($this->sections[$section])) {
			return $this->sections[$section];
		}

		return $this->sections[$section] = parent::getSection($section, $class);
	}



	public function hasSection($section)
	{
		return isset($this->sections[$section]);
	}



	public function getIterator()
	{
		return new \ArrayIterator();
	}



	public function clean()
	{
		// nope
	}



	public function setStorage(ISessionStorage $storage)
	{
		return parent::setStorage($storage);
	}



	public function setHandler(\SessionHandlerInterface $handler)
	{
		return parent::setHandler($handler);
	}

}
