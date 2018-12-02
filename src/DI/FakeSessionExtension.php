<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\FakeSession\DI;

use Kdyby;
use Kdyby\FakeSession\Session;
use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\Http\Session as NetteSession;

class FakeSessionExtension extends \Nette\DI\CompilerExtension
{

	/**
	 * @var mixed[]
	 */
	public $defaults = [
		'enabled' => '%consoleMode%',
	];

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$originalServiceName = $builder->getByType(NetteSession::class) ?: 'session';
		$original = $builder->getDefinition($originalServiceName);
		$builder->removeDefinition($originalServiceName);
		$builder->addDefinition($this->prefix('original'), $original)
			->setAutowired(FALSE);

		$session = $builder->addDefinition($originalServiceName)
			->setClass(NetteSession::class)
			->setFactory(Session::class, [$this->prefix('@original')]);

		if ($config['enabled']) {
			$session->addSetup('disableNative');
		}
	}

	public static function register(Configurator $configurator)
	{
		$configurator->onCompile[] = function ($config, Compiler $compiler) {
			$compiler->addExtension('fakeSession', new FakeSessionExtension());
		};
	}

}
