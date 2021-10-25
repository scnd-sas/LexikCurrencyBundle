<?php

namespace Lexik\Bundle\CurrencyBundle\Adapter;

/**
 * @author Cédric Girard <c.girard@lexik.fr>
 * @author Yoann Aparici <y.aparici@lexik.fr>
 */
class EcbCurrencyAdapter extends AbstractCurrencyAdapter
{
    /**
     * @var string
     */
    private $ecbUrl;

    public function setEcbUrl(string $url): void
    {
        $this->ecbUrl = $url;
    }

    /**
     * Init object storage
     */
    public function attachAll(): void
    {
        $defaultRate = 1;

        // Add euro
        $euro = new $this->currencyClass;
        $euro->setCode('EUR');
        $euro->setRate(1);

        $this[$euro->getCode()] = $euro;

        // Get other currencies
        $xml = @simplexml_load_string(file_get_contents($this->ecbUrl));

        if ($xml instanceof \SimpleXMLElement) {
            $data = $xml->xpath('//gesmes:Envelope/*[3]/*');

            foreach ($data[0]->children() as $child) {
                $code = (string) $child->attributes()->currency;

                if (in_array($code, $this->managedCurrencies, true)) {
                    $currency = new $this->currencyClass;
                    $currency->setCode($code);
                    $currency->setRate((string) $child->attributes()->rate);

                    $this[$currency->getCode()] = $currency;
                }
            }

            if (isset($this[$this->defaultCurrency])) {
                $defaultRate = $this[$this->defaultCurrency]->getRate();
            }

            $this->convertAll($defaultRate);
        }
    }

    public function getIdentifier(): string
    {
        return 'ecb';
    }
}
