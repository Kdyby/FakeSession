<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Kdyby\FakeSession;

use ArrayIterator;
use Kdyby;
use Nette\Http\Session as NetteSession;

class SessionSection extends \Nette\Http\SessionSection
{

	/** @var mixed[] */
	private $data = [];

	public function __construct(NetteSession $session, $name)
	{
		parent::__construct($session, $name);
	}

	public function getIterator()
	{
		return new ArrayIterator($this->data);
	}

	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}

	public function &__get($name)
	{
		if ($this->warnOnUndefined && !array_key_exists($name, $this->data)) {
			trigger_error(sprintf("The variable '%s' does not exist in session section", $name), E_USER_NOTICE);
		}

		return $this->data[$name];
	}

	public function __isset($name)
	{
		return isset($this->data[$name]);
	}

	public function __unset($name)
	{
		unset($this->data[$name]);
	}

	public function setExpiration($time, $variables = NULL)
	{
		return $this;
	}

	public function removeExpiration($variables = NULL)
	{
		//
	}

	public function remove()
	{
		$this->data = [];
	}

}
