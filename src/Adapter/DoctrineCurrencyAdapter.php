<?php

namespace Lexik\Bundle\CurrencyBundle\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

/**
 * @author Yoann Aparici <y.aparici@lexik.fr>
 * @author Cédric Girard <c.girard@lexik.fr>
 */
class DoctrineCurrencyAdapter extends AbstractCurrencyAdapter
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * {@inheritdoc}
     */
    public function attachAll(): void
    {
        // nothing here
    }

    public function getIdentifier(): string
    {
        return 'doctrine';
    }

    public function setManager(EntityManagerInterface $manager): void
    {
        $this->manager = $manager;
    }

    public function offsetExists(mixed $index): bool
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return parent::offsetExists($index);
    }

    public function offsetGet(mixed $index): mixed
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return parent::offsetGet($index);
    }

    private function initialize(): void
    {
        if (!isset($this->manager)) {
            throw new RuntimeException('No ObjectManager set on DoctrineCurrencyAdapter.');
        }

        $currencies = $this->manager
            ->getRepository($this->currencyClass)
            ->findAll();

        foreach ($currencies as $currency) {
            $this[$currency->getCode()] = $currency;
        }

        $this->initialized = true;
    }
}
