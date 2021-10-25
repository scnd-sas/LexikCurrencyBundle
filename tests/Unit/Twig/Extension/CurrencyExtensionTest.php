<?php

namespace Lexik\Bundle\CurrencyBundle\Tests\Unit\Twig\Extension;

use Lexik\Bundle\CurrencyBundle\Currency\Converter;
use Lexik\Bundle\CurrencyBundle\Adapter\AdapterFactory;
use Lexik\Bundle\CurrencyBundle\Currency\Formatter;
use Lexik\Bundle\CurrencyBundle\Twig\Extension\CurrencyExtension;
use Lexik\Bundle\CurrencyBundle\Tests\Unit\BaseUnitTestCase;
use Lexik\Bundle\CurrencyBundle\Entity\Currency;

class CurrencyExtensionTest extends BaseUnitTestCase
{
    private const CURRENCY_ENTITY = Currency::class;

    protected $doctrine;

    private $converter;
    private $formatter;

    public function setUp(): void
    {
        $this->doctrine = $this->getMockDoctrine();
        $em = $this->getEntityManager();

        $this->createSchema($em);
        $this->loadFixtures($em);

        $factory = new AdapterFactory($this->doctrine, 'EUR', ['EUR', 'USD'], self::CURRENCY_ENTITY);

        $this->converter = new Converter($factory->createDoctrineAdapter());
        $this->formatter = new Formatter('fr');
    }

    public function testConvert()
    {
        $extension = new CurrencyExtension($this->formatter, $this->converter);

        $this->assertEquals(11.27, $extension->convert(8.666, 'USD'));
        $this->assertEquals(8.67, $extension->convert(8.666, 'EUR'));
    }

    public function testFormat()
    {
        $extension = new CurrencyExtension($this->formatter, $this->converter);

        $this->assertEquals('8,67 €', $extension->format(8.666));
        $this->assertEquals('8,67 €', $extension->format(8.666, 'EUR'));
        $this->assertEquals('8,67 $', $extension->format(8.666, 'USD'));
        $this->assertEquals('8 $', $extension->format(8.0, 'USD', false));
        $this->assertEquals('8,666', $extension->format(8.666, 'USD', false, false));
        $this->assertEquals('8', $extension->format(8.0, 'USD', true, false));
        $this->assertEquals('8 $', $extension->format(8.0, 'USD', false, true));
    }

    public function testConvertAndFormat()
    {
        $extension = new CurrencyExtension($this->formatter, $this->converter);

        $this->assertEquals('11,27 $', $extension->convertAndFormat(8.666, 'USD'));
        $this->assertEquals('11,27 $', $extension->convertAndFormat(8.666, 'USD', false));
        $this->assertEquals('11,2658', $extension->convertAndFormat(8.666, 'USD', false, false));
        $this->assertEquals('8,67', $extension->convertAndFormat(8.666, 'USD', true, false, 'USD'));
        $this->assertEquals('8', $extension->convertAndFormat(8.0, 'USD', true, false, 'USD'));
        $this->assertEquals('8,00 $', $extension->convertAndFormat(8.0, 'USD', true, true, 'USD'));
        $this->assertEquals('8 $', $extension->convertAndFormat(8.0, 'USD', false, true, 'USD'));
    }
}
