<?php

namespace Lexik\Bundle\CurrencyBundle\Adapter;

use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * This class is used to create currency adapters
 *
 * @author Yoann Aparici <y.aparici@lexik.fr>
 * @author Cédric Girard <c.girard@lexik.fr>
 */
class AdapterFactory
{
    private $doctrine;
    private $currencies;
    private $currencyClass;

    public function __construct(Registry $doctrine, string $defaultCurrency, array $availableCurrencies, string $currencyClass)
    {
        $this->doctrine = $doctrine;

        $this->currencies = [];
        $this->currencies['default'] = $defaultCurrency;
        $this->currencies['managed'] = $availableCurrencies;
        $this->currencyClass = $currencyClass;
    }

    /**
     * Create an adapter from the given class.
     */
    public function create(string $adapterClass): AbstractCurrencyAdapter
    {
        $adapter = new $adapterClass();
        $adapter->setDefaultCurrency($this->currencies['default']);
        $adapter->setManagedCurrencies($this->currencies['managed']);
        $adapter->setCurrencyClass($this->currencyClass);

        return $adapter;
    }

    /**
     * @return DoctrineCurrencyAdapter
     */
    public function createDoctrineAdapter(string $adapterClass = DoctrineCurrencyAdapter::class, string $entityManagerName = null): AbstractCurrencyAdapter
    {
        $adapter = $this->create($adapterClass);

        $em = $this->doctrine->getManager($entityManagerName);
        $adapter->setManager($em);

        return $adapter;
    }

    /**
     * @return EcbCurrencyAdapter
     */
    public function createEcbAdapter(string $adapterClass = EcbCurrencyAdapter::class): AbstractCurrencyAdapter
    {
        return $this->create($adapterClass);
    }

    /**
     * @return OerCurrencyAdapter
     */
    public function createOerAdapter(string $adapterClass = OerCurrencyAdapter::class): AbstractCurrencyAdapter
    {
        return $this->create($adapterClass);
    }

    /**
     * @return YahooCurrencyAdapter
     */
    public function createYahooAdapter(string $adapterClass = YahooCurrencyAdapter::class): AbstractCurrencyAdapter
    {
        return $this->create($adapterClass);
    }
}
