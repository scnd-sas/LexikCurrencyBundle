<?php

namespace Lexik\Bundle\CurrencyBundle\Twig\Extension;

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
    public function getFilters(): array
    {
        return [
            new TwigFilter('currency_convert', [CurrencyRuntime::class, 'convert']),
            new TwigFilter('currency_format', [CurrencyRuntime::class, 'format']),
            new TwigFilter('currency_convert_format', [CurrencyRuntime::class, 'convertAndFormat']),
        ];
    }
}
