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
use Nette\PhpGenerator as Code;



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



	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$originalSession = $builder->getDefinition('session');
		$builder->removeDefinition('session');
		$originalSession = $builder->addDefinition($this->prefix('original'), $originalSession)
			->setAutowired(FALSE)
			->setInject(FALSE);

		$builder->addDefinition($this->prefix('factory'))
			->setClass('Kdyby\FakeSession\SessionFactory', [
				$originalSession,
			]);

		$builder->addDefinition('session')
			->setClass('Nette\Http\Session')
			->setFactory(new Nette\DI\Statement('@Kdyby\FakeSession\SessionFactory::create', [
				$config['enabled'],
			]));
	}

}
