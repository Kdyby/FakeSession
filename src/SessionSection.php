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


	/**
	 * @return Iterator<mixed>
	 */
	public function getIterator(): Iterator
	{
		return new ArrayIterator($this->data);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set(string $name, $value): void
	{
		$this->data[$name] = $value;
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function &__get(string $name)
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
		unset($this->data[$name]);
	}

	/**
	 * @param string|int|\DateTimeInterface $time
	 * @param string|string[] $variables list of variables / single variable to expire
	 * @return static
	 */
	public function setExpiration($time, $variables = NULL): self
	{
		return $this;
	}

	/**
	 * @param string|string[] $variables list of variables / single variable to expire
	 */
	public function removeExpiration($variables = NULL): void
	{
	}

	public function remove(): void
	{
		$this->data = [];
	}

}
