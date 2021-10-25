<?php

namespace Lexik\Bundle\CurrencyBundle\Adapter;

use InvalidArgumentException;

/**
 * @author Yoann Aparici <y.aparici@lexik.fr>
 */
final class AdapterCollector implements AdapterCollectorInterface
{
    private $elements = [];

    /**
     * {@inheritdoc}
     */
    public function add(AbstractCurrencyAdapter $adapter): void
    {
        $this->elements[$adapter->getIdentifier()] = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key): AbstractCurrencyAdapter
    {
        if (!isset($this->elements[$key])) {
            throw new InvalidArgumentException(sprintf('Adapter "%s" does not exist', $key));
        }

        return $this->elements[$key];
    }
}
