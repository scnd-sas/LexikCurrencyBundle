<?php

namespace Lexik\Bundle\CurrencyBundle\Currency;

/**
 * @author Cédric Girard <c.girard@lexik.fr>
 */
interface FormatterInterface
{
    /**
     * Format a given value.
     */
    public function format(float $value, string $valueCurrency = null, bool $decimal = true, bool $symbol = true): string;

    /**
     * Set the locale to use to format the value.
     */
    public function setLocale(string $locale): void;
}
