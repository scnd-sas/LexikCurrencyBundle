<?php

namespace Lexik\Bundle\CurrencyBundle\Twig\Extension;

use Lexik\Bundle\CurrencyBundle\Currency\ConverterInterface;
use Lexik\Bundle\CurrencyBundle\Currency\FormatterInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\RuntimeExtensionInterface;

class CurrencyRuntime implements RuntimeExtensionInterface
{
    private $formatter;
    private $converter;

    public function __construct(FormatterInterface $formatter, ConverterInterface $converter)
    {
        $this->formatter = $formatter;
        $this->converter = $converter;
    }

    /**
     * Convert the given value.
     */
    public function convert(float $value, string $targetCurrency, bool $round = true, string $valueCurrency = null): float
    {
        return $this->converter->convert($value, $targetCurrency, $round, $valueCurrency);
    }

    /**
     * Format the given value.
     */
    public function format(float $value, string $valueCurrency = null, bool $decimal = true, bool $symbol = true): string
    {
        if (null === $valueCurrency) {
            $valueCurrency = $this->converter->getDefaultCurrency();
        }

        return $this->formatter->format($value, $valueCurrency, $decimal, $symbol);
    }

    /**
     * Convert and format the given value.
     */
    public function convertAndFormat(float $value, string $targetCurrency = null, bool $decimal = true, bool $symbol = true, string $valueCurrency = null): string
    {
        $value = $this->convert($value, $targetCurrency, $decimal, $valueCurrency);

        return $this->format($value, $targetCurrency, $decimal, $symbol);
    }
}
