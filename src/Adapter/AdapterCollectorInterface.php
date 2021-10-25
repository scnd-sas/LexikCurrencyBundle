<?php


namespace Lexik\Bundle\CurrencyBundle\Adapter;


interface AdapterCollectorInterface
{
    /**
     * Adds an adapter
     */
    public function add(AbstractCurrencyAdapter $adapter): void;

    /**
     * Gets an adapter
     */
    public function get(string $key): AbstractCurrencyAdapter;
}
