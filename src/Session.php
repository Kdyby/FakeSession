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
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Nette\Http\Session as NetteSession;
use Nette\Http\SessionSection as NetteSessionSection;
use SessionHandlerInterface;

class Session extends \Nette\Http\Session
{

	/**
	 * @var array|\Nette\Http\SessionSection[]
	 */
	private $sections = [];

	/**
	 * @var bool
	 */
	private $started = FALSE;

	/**
	 * @var bool
	 */
	private $exists = FALSE;

	/**
	 * @var string
	 */
	private $id = '';

	/**
	 * @var \Nette\Http\Session
	 */
	private $originalSession;

	/**
	 * @var bool
	 */
	private $fakeMode = FALSE;

	public function __construct(NetteSession $originalSession, IRequest $request, IResponse $response)
	{
		$this->originalSession = $originalSession;
	}

	public function disableNative(): void
	{
		if ($this->originalSession->isStarted()) {
			throw new \LogicException('Session is already started, please close it first and then you can disabled it.');
		}

		$this->fakeMode = TRUE;
	}

	public function enableNative(): void
	{
		$this->fakeMode = FALSE;
	}

	public function isNativeEnabled(): bool
	{
		return !$this->fakeMode;
	}

	public function start(): void
	{
		if (!$this->fakeMode) {
			$this->originalSession->start();
		}
	}

	public function isStarted(): bool
	{
		if (!$this->fakeMode) {
			return $this->originalSession->isStarted();
		}

		return $this->started;
	}

	public function setFakeStarted(bool $started): void
	{
		$this->started = $started;
	}

	public function close(): void
	{
		if (!$this->fakeMode) {
			$this->originalSession->close();
		}
	}

	public function destroy(): void
	{
		if (!$this->fakeMode) {
			$this->originalSession->destroy();
		}
	}

	public function exists(): bool
	{
		if (!$this->fakeMode) {
			return $this->originalSession->exists();
		}

		return $this->exists;
	}

	public function setFakeExists(bool $exists): void
	{
		$this->exists = $exists;
	}

	public function regenerateId(): void
	{
		if (!$this->fakeMode) {
			$this->originalSession->regenerateId();
		}
	}

	public function getId(): string
	{
		if (!$this->fakeMode) {
			return $this->originalSession->getId();
		}

		return $this->id;
	}

	public function setFakeId(string $id): void
	{
		$this->id = $id;
	}

	public function getSection(string $section, string $class = NetteSessionSection::class): NetteSessionSection
	{
		if (!$this->fakeMode) {
			return $this->originalSession->getSection($section, $class);
		}

		if (isset($this->sections[$section])) {
			return $this->sections[$section];
		}

		return $this->sections[$section] = parent::getSection($section, $class !== NetteSessionSection::class ? $class : SessionSection::class);
	}

	public function hasSection(string $section): bool
	{
		if (!$this->fakeMode) {
			return $this->originalSession->hasSection($section);
		}

		return isset($this->sections[$section]);
	}

	public function getIterator(): Iterator
	{
		if (!$this->fakeMode) {
			return $this->originalSession->getIterator();
		}

		return new ArrayIterator(array_keys($this->sections));
	}

	public function setName(string $name): static
	{
		$this->originalSession->setName($name);

		return $this;
	}

	public function getName(): string
	{
		return $this->originalSession->getName();
	}

	/**
	 * @param mixed[] $options
	 * @return static
	 */
	public function setOptions(array $options): static
	{
		$this->originalSession->setOptions($options);

		return $this;
	}

	/**
	 * @return mixed[]
	 */
	public function getOptions(): array
	{
		return $this->originalSession->getOptions();
	}

	public function setExpiration(?string $time): static
	{
		$this->originalSession->setExpiration($time);

		return $this;
	}

	public function setCookieParameters(string $path, ?string $domain = NULL, ?bool $secure = NULL, ?string $sameSite = NULL): static
	{
		$this->originalSession->setCookieParameters($path, $domain, $secure, $sameSite);

		return $this;
	}

	/**
	 * @return mixed[]
	 */
	public function getCookieParameters(): array
	{
		return $this->originalSession->getCookieParameters();
	}

	public function setSavePath(string $path): static
	{
		$this->originalSession->setSavePath($path);

		return $this;
	}

	public function setHandler(SessionHandlerInterface $handler): static
	{
		$this->originalSession->setHandler($handler);

		return $this;
	}

}
