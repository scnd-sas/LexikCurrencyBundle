<?php

namespace Lexik\Bundle\CurrencyBundle\Adapter;

use ArrayIterator;
use InvalidArgumentException;
use Lexik\Bundle\CurrencyBundle\Entity\Currency;

/**
 *
 * @author Cédric Girard <c.girard@lexik.fr>
 * @author Yoann Aparici <y.aparici@lexik.fr>
 */
abstract class AbstractCurrencyAdapter extends ArrayIterator
{
    /**
     * @var string
     */
    protected $defaultCurrency;

    /**
     * @var array
     */
    protected $managedCurrencies = [];

    /**
     * @var string
     */
    protected $currencyClass;

    public function setDefaultCurrency(string $defaultCurrency): void
    {
        $this->defaultCurrency = $defaultCurrency;
    }

    public function getDefaultCurrency(): string
    {
        return $this->defaultCurrency;
    }

    public function setManagedCurrencies(array $currencies): void
    {
        $this->managedCurrencies = $currencies;
    }

    public function getManagedCurrencies(): array
    {
        return $this->managedCurrencies;
    }

    public function setCurrencyClass(string $currencyClass): void
    {
        $this->currencyClass = $currencyClass;
    }

    /**
     * Set object
     *
     * @param mixed $index
     * @param Currency $newval
     */
    public function offsetSet($index, $newval): void
    {
        if (!$newval instanceof $this->currencyClass) {
            throw new InvalidArgumentException(sprintf('$newval must be an instance of Currency, instance of "%s" given', get_class($newval)));
        }

        parent::offsetSet($index, $newval);
    }

    /**
     * Append a value
     *
     * @param Currency $value
     */
    public function append($value): void
    {
        if (!$value instanceof $this->currencyClass) {
            throw new InvalidArgumentException(sprintf('$newval must be an instance of Currency, instance of "%s" given', get_class($value)));
        }

        parent::append($value);
    }

    protected function convertAll(float $rate): void
    {
        /** @var Currency $currency */
        foreach ($this as $currency) {
            $currency->convert($rate);
        }
    }

    /**
     * This method is used by the constructor
     * to attach all currencies.
     */
    abstract public function attachAll(): void;

    /**
     * Get identifier value for the adapter must be unique
     * for all the project
     */
    abstract public function getIdentifier(): string;
}
