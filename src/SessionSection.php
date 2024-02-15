<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Kdyby\FakeSession;

use ArrayIterator;
use Iterator;
use Kdyby;
use Nette\Http\Session as NetteSession;

class SessionSection extends \Nette\Http\SessionSection
{

	/** @var mixed[] */
	private $data = [];

	public function __construct(NetteSession $session, string $name)
	{
		parent::__construct($session, $name);
	}

	public function getIterator(): Iterator
	{
		return new ArrayIterator($this->data);
	}


	/** @param mixed $value */
	public function set(string $name, $value, ?string $expiration = NULL): void
	{
		if ($value === NULL) {
			$this->remove($name);
		} else {
			$this->__set($name, $value);
		}
	}

	public function get(string $name): mixed
	{
		return $this->__get($name);
	}

	/** @param mixed $value */
	public function __set(string $name, $value): void
	{
		$this->data[$name] = $value;
	}

	public function &__get(string $name): mixed
	{
		if ($this->warnOnUndefined && !array_key_exists($name, $this->data)) {
			trigger_error(sprintf("The variable '%s' does not exist in session section", $name), E_USER_NOTICE);
		}

		return $this->data[$name];
	}

	public function __isset(string $name): bool
	{
		return isset($this->data[$name]);
	}

	public function __unset(string $name): void
	{
		$this->remove($name);
	}

	/**
	 * @param string|int|\DateTimeInterface $time
	 * @param string|string[] $variables list of variables / single variable to expire
	 * @return static
	 */
	public function setExpiration($time, $variables = NULL): static
	{
		return $this;
	}

	/**
	 * @param string|string[] $variables list of variables / single variable to expire
	 */
	public function removeExpiration($variables = NULL): void
	{
	}

	/** @param string|string[]|null $name */
	public function remove($name = null): void
	{
		if ($name !== NULL) {
			foreach ((array) $name as $name) {
				unset($this->data[$name]);
			}
		} else {
			$this->data = [];
		}
	}

}
