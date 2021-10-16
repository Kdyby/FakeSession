<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Kdyby\FakeSession\DI;

use Kdyby;
use Kdyby\FakeSession\Session;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\Http\Session as NetteSession;

class FakeSessionExtension extends \Nette\DI\CompilerExtension
{

	/**
	 * @var mixed[]
	 */
	public $defaults = [
		'enabled' => NULL,
	];

	public function __construct()
	{
		$this->defaults['enabled'] = $this->defaults['enabled'] ?? (PHP_SAPI === 'cli');
	}

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		$originalServiceName = $builder->getByType(NetteSession::class) ?: 'session';
		$original = $builder->getDefinition($originalServiceName);
		$builder->removeDefinition($originalServiceName);
		$builder->addDefinition($this->prefix('original'), clone $original)
			->setAutowired(FALSE);

		$session = $builder->addDefinition($originalServiceName, new ServiceDefinition())
			->setType(NetteSession::class)
			->setFactory(Session::class, [$this->prefix('@original')]);

		if ($config['enabled']) {
			$session->addSetup('disableNative');
		}
	}

}
