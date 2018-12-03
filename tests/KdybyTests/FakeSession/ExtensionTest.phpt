<?php

/**
 * Test: Kdyby\Doctrine\Extension.
 *
 * @testCase Kdyby\Doctrine\ExtensionTest
 */

declare(strict_types = 1);

namespace KdybyTests\FakeSession;

use Kdyby;
use Kdyby\FakeSession\DI\FakeSessionExtension;
use Kdyby\FakeSession\Session;
use Nette\Configurator;
use Nette\DI\Container;
use Nette\Http\Session as NetteSession;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';



class ExtensionTest extends \Tester\TestCase
{

	public function createContainer(string $configName): Container
	{
		$config = new Configurator();
		$config->setTempDirectory(TEMP_DIR);
		$config->addConfig(__DIR__ . '/../nette-reset.neon');
		$config->addConfig(__DIR__ . '/config/' . $configName . '.neon');
		FakeSessionExtension::register($config);

		return $config->createContainer();
	}

	public function testEnabledFunctionality(): void
	{
		$container = $this->createContainer('enabled');
		$session = $container->getByType(NetteSession::class);
		Assert::true($session instanceof Session);
		Assert::false($session->isNativeEnabled());
	}

	public function testDisabledFunctionality(): void
	{
		$container = $this->createContainer('disabled');
		$session = $container->getByType(NetteSession::class);
		Assert::true($session instanceof Session);
		Assert::true($session->isNativeEnabled());
	}

}

(new ExtensionTest())->run();
