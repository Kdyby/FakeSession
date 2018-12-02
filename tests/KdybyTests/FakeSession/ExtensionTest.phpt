<?php

/**
 * Test: Kdyby\Doctrine\Extension.
 *
 * @testCase Kdyby\Doctrine\ExtensionTest
 */

namespace KdybyTests\FakeSession;

use Kdyby;
use Kdyby\FakeSession\DI\FakeSessionExtension;
use Kdyby\FakeSession\Session;
use Nette\Configurator;
use Nette\Http\Session as NetteSession;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';



class ExtensionTest extends \Tester\TestCase
{

	/**
	 * @param string $configName
	 * @return \Nette\DI\Container
	 */
	public function createContainer($configName)
	{
		$config = new Configurator();
		$config->setTempDirectory(TEMP_DIR);
		$config->addConfig(__DIR__ . '/../nette-reset.neon');
		$config->addConfig(__DIR__ . '/config/' . $configName . '.neon');
		FakeSessionExtension::register($config);

		return $config->createContainer();
	}

	public function testEnabledFunctionality()
	{
		$container = $this->createContainer('enabled');
		$session = $container->getByType(NetteSession::class);
		Assert::true($session instanceof Session);
		Assert::false($session->isNativeEnabled());
	}

	public function testDisabledFunctionality()
	{
		$container = $this->createContainer('disabled');
		$session = $container->getByType(NetteSession::class);
		Assert::true($session instanceof Session);
		Assert::true($session->isNativeEnabled());
	}

}

(new ExtensionTest())->run();
