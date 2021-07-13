<?php

namespace App\Service;

use App\Entity\CryptocurrencyData;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class CallApi extends AbstractController {

    private $_client;
    private $_params;

    public function __construct(HttpClientInterface $client, ContainerBagInterface $params) {
        $this->_client = $client;
        $this->_params = $params;
    }

    private function requestApi(string $name, string $currency): array
    {
        $apiKey = $this->_params->get('CRYPTOCOMPARE_API');
        $response = $this->_client->request(
            'GET',
            "https://min-api.cryptocompare.com/data/pricemultifull?fsyms={$name}&tsyms={$currency}&api_key={$apiKey}"
        );

        $statusCode = $response->getStatusCode();
        $dataType = $response->getHeaders()['content-type'][0];
        $data = $response->getContent();
        $data = $response->toArray();

        return $data['RAW'];
    }

    public function fetchCryptocurrencyData(string $name, string $currency)
    {        
        $data = $this->requestApi($name, $currency);

        $cryptocurrencyData = new CryptocurrencyData();
        $cryptocurrencyData->setCurrency($data[$name][$currency]['TOSYMBOL']);
        $cryptocurrencyData->setPrice($data[$name][$currency]['PRICE']);
        $cryptocurrencyData->setMarketCap($data[$name][$currency]['MKTCAP']);
        $cryptocurrencyData->setVolume24h($data[$name][$currency]['VOLUME24HOURTO']);
        $cryptocurrencyData->setCirculatingSupply($data[$name][$currency]['SUPPLY']);
        $cryptocurrencyData->setUpdateAt(new \DateTimeImmutable("now"));
                 
        $dataObject = $cryptocurrencyData;
        
        return $dataObject;
    }

    public function updateCryptocurrencyData()
    {
        //récupère toutes les cryptomonnaies en base de données.
        $product = $this->getDoctrine()
            ->getRepository(CryptocurrencyData::class)
            ->findAll();
        
        //pour chaque cryptomonnaies je garde le nom que j'insère dans un tableau.
        foreach ($product as $value) {
            $name[] = $value->getCryptocurrencies()->getName();
            $currency = $value->getCurrency();
        }

        //transforme le tableau en chaîne de caractères.
        $arrayToString = implode(",", $name);

        //recupère les données 
        $data = $this->requestApi($arrayToString, $currency);
        dump($data);

        //Pour chaque entrée du tableau mettre à jour les valeurs.
        
        
        
        
    }

}
