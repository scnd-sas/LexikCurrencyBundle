<?php

namespace Lexik\Bundle\CurrencyBundle\Tests\Unit\Adapter;

use Lexik\Bundle\CurrencyBundle\Adapter\AdapterFactory;
use Lexik\Bundle\CurrencyBundle\Tests\Unit\BaseUnitTestCase;
use Lexik\Bundle\CurrencyBundle\Entity\Currency;
use Lexik\Bundle\CurrencyBundle\Adapter\EcbCurrencyAdapter;
use Lexik\Bundle\CurrencyBundle\Adapter\YahooCurrencyAdapter;
use Lexik\Bundle\CurrencyBundle\Adapter\DoctrineCurrencyAdapter;

class AdapterFactoryTest extends BaseUnitTestCase
{
    private const CURRENCY_ENTITY = Currency::class;

    protected $doctrine;

    protected function setUp(): void
    {
        $this->doctrine = $this->getMockDoctrine();
        $em = $this->getEntityManager();
        $this->createSchema($em);
    }

    public function testCreateEcbAdapter()
    {
        $factory = new AdapterFactory($this->doctrine, 'EUR', ['EUR', 'USD'], self::CURRENCY_ENTITY);
        $adapter = $factory->createEcbAdapter();

        $this->assertInstanceOf(EcbCurrencyAdapter::class, $adapter);
        $this->assertEquals('EUR', $adapter->getDefaultCurrency());
        $this->assertEquals(['EUR', 'USD'], $adapter->getManagedCurrencies());
        $this->assertCount(0, $adapter);
    }

    public function testCreateYahooAdapter()
    {
        $factory = new AdapterFactory($this->doctrine, 'EUR', ['EUR', 'USD'], self::CURRENCY_ENTITY);
        $adapter = $factory->createYahooAdapter();

        $this->assertInstanceOf(YahooCurrencyAdapter::class, $adapter);
        $this->assertEquals('EUR', $adapter->getDefaultCurrency());
        $this->assertEquals(array('EUR', 'USD'), $adapter->getManagedCurrencies());
        $this->assertCount(0, $adapter);
    }

    public function testCreateDoctrineAdapter()
    {
        $em = $this->getEntityManager();
        $this->loadFixtures($em);

        $factory = new AdapterFactory($this->doctrine, 'USD', ['EUR'], self::CURRENCY_ENTITY);
        $adapter = $factory->createDoctrineAdapter();

        $this->assertInstanceOf(DoctrineCurrencyAdapter::class, $adapter);
        $this->assertEquals('USD', $adapter->getDefaultCurrency());
        $this->assertEquals(['EUR'], $adapter->getManagedCurrencies());
        $this->assertCount(0, $adapter);

        $adapter['USD']; // force initialization
        $this->assertCount(2, $adapter);
    }
}
