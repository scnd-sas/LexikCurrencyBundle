<?php

namespace Lexik\Bundle\CurrencyBundle\Twig\Extension;

use Lexik\Bundle\CurrencyBundle\Currency\ConverterInterface;
use Lexik\Bundle\CurrencyBundle\Currency\FormatterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Twig extension to format and convert currencies from templates.
 *
 * @author Cédric Girard <c.girard@lexik.fr>
 * @author Yoann Aparici <y.aparici@lexik.fr>
 */
class CurrencyExtension extends AbstractExtension
{
    private $formatter;
    private $converter;

    public function __construct(FormatterInterface $formatter, ConverterInterface $converter)
    {
        $this->formatter = $formatter;
        $this->converter = $converter;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('currency_convert', [$this, 'convert']),
            new TwigFilter('currency_format', [$this, 'format']),
            new TwigFilter('currency_convert_format', [$this, 'convertAndFormat']),
        ];
    }

    public function getConverter(): ConverterInterface
    {
        return $this->converter;
    }

    public function getFormatter(): FormatterInterface
    {
        return $this->formatter;
    }

    /**
     * Convert the given value.
     */
    public function convert(float $value, string $targetCurrency, bool $round = true, string $valueCurrency = null): float
    {
        return $this->getConverter()->convert($value, $targetCurrency, $round, $valueCurrency);
    }

    /**
     * Format the given value.
     */
    public function format(float $value, string $valueCurrency = null, bool $decimal = true, bool $symbol = true): string
    {
        if (null === $valueCurrency) {
            $valueCurrency = $this->getConverter()->getDefaultCurrency();
        }

        return $this->getFormatter()->format($value, $valueCurrency, $decimal, $symbol);
    }

    /**
     * Convert and format the given value.
     */
    public function convertAndFormat(float $value, string $targetCurrency = null, bool $decimal = true, bool $symbol = true, string $valueCurrency = null): string
    {
        $value = $this->convert($value, $targetCurrency, $decimal, $valueCurrency);

        return $this->format($value, $targetCurrency, $decimal, $symbol);
    }

    public function getName(): string
    {
        return 'lexik_currency.currency_extension';
    }
}
