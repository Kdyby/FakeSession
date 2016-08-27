<?php

/**
 * Test: Kdyby\Doctrine\Extension.
 *
 * @testCase Kdyby\Doctrine\ExtensionTest
 * @author Filip Procházka <filip@prochazka.su>
 * @package Kdyby\Doctrine
 */

namespace KdybyTests\FakeSession;

use Doctrine;
use Kdyby;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class ExtensionTest extends Tester\TestCase
{

	/**
	 * @return Nette\DI\Container
	 */
	public function createContainer($configName)
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);
		$config->addConfig(__DIR__ . '/../nette-reset.neon');
		$config->addConfig(__DIR__ . '/config/' . $configName . '.neon');
		Kdyby\FakeSession\DI\FakeSessionExtension::register($config);

		return $config->createContainer();
	}



	public function testEnabledFunctionality()
	{
		$container = $this->createContainer('enabled');
		$session = $container->getByType('Nette\Http\Session');
		Assert::true($session instanceof Kdyby\FakeSession\Session);
		Assert::false($session->isNativeEnabled());
	}



	public function testDisabledFunctionality()
	{
		$container = $this->createContainer('disabled');
		$session = $container->getByType('Nette\Http\Session');
		Assert::true($session instanceof Kdyby\FakeSession\Session);
		Assert::true($session->isNativeEnabled());
	}

}

\run(new ExtensionTest());
