<?php

namespace Lexik\Bundle\CurrencyBundle\Currency;

use NumberFormatter;

/**
 * Currency formatter.
 *
 * @author Cédric Girard <c.girard@lexik.fr>
 */
class Formatter implements FormatterInterface
{
    protected $locale;
    protected $cleanCharacters;

    public function __construct(string $locale, array $cleanCharacters = ['EU', 'UK', 'US'])
    {
        $this->locale = $locale;
        $this->cleanCharacters = $cleanCharacters;
    }

    /**
     * {@inheritdoc}
     */
    public function format(float $value, string $valueCurrency = null, bool $decimal = true, bool $symbol = true): string
    {
        $formatter = new NumberFormatter($this->locale, $symbol ? NumberFormatter::CURRENCY : NumberFormatter::DECIMAL);

        if (!$symbol) {
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 4);
        }

        $formattedValue = $formatter->formatCurrency($value, $valueCurrency);

        if (!$decimal) {
            $formattedValue = preg_replace('/[.,]00((?=\D)|$)/', '', $formattedValue);
        }

        if (count($this->cleanCharacters) > 0) {
            $formattedValue = str_replace($this->cleanCharacters, '', $formattedValue);
        }

        return $formattedValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function setCleanCharacters(array $cleanCharacters): void
    {
        $this->cleanCharacters = $cleanCharacters;
    }
}
