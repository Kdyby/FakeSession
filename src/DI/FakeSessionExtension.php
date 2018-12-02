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
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class FakeSessionExtension extends Nette\DI\CompilerExtension
{

	/**
	 * @var array
	 */
	public $defaults = [
		'enabled' => '%consoleMode%',
	];



	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$originalServiceName = $builder->getByType(Nette\Http\Session::class) ?: 'session';
		$original = $builder->getDefinition($originalServiceName);
		$builder->removeDefinition($originalServiceName);
		$builder->addDefinition($this->prefix('original'), $original)
			->setAutowired(FALSE);

		$session = $builder->addDefinition($originalServiceName)
			->setClass(Nette\Http\Session::class)
			->setFactory(Kdyby\FakeSession\Session::class, [$this->prefix('@original')]);

		if ($config['enabled']) {
			$session->addSetup('disableNative');
		}
	}



	public static function register(Nette\Configurator $configurator)
	{
		$configurator->onCompile[] = function ($config, Nette\DI\Compiler $compiler) {
			$compiler->addExtension('fakeSession', new FakeSessionExtension());
		};
	}

}
