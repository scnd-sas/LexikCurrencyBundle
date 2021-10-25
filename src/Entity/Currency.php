<?php

namespace Lexik\Bundle\CurrencyBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Yoann Aparici <y.aparici@lexik.fr>
 */
class Currency
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @Assert\Length(min=3)
     * @Assert\Length(max=3)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @var string
     */
    protected $code;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     *
     * @var string
     */
    protected $rate;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getRate(): float
    {
        return (float) $this->rate;
    }

    public function setRate(float $rate): void
    {
        $this->rate = (string) $rate;
    }

    /**
     * Convert currency rate
     */
    public function convert(float $rate): void
    {
        $this->rate /= $rate;
    }
}
