<?php

namespace App\Service;

use App\Entity\Cryptocurrencies;
use App\Entity\CryptocurrencyData;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class CallApi extends AbstractController {

    private $_client;
    private $_params;

    public function __construct(HttpClientInterface $client, ContainerBagInterface $params) {
        $this->_client = $client;
        $this->_params = $params;
    }

    private function requestApi(string $name, string $currency = 'EUR'): array
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

    public function fetchLogo(string $name, string $currency = 'EUR')
    {
        $data = $this->requestApi($name, $currency);
        
        $logo = 'https://www.cryptocompare.com'. $data[$name][$currency]['IMAGEURL'];

        return $logo;
    }

    public function fetchCryptocurrencyData(string $name, string $currency = 'EUR')
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
        $entityManager = $this->getDoctrine()->getManager();

        $allData = $entityManager->getRepository(CryptocurrencyData::class)->findAll();
        
        foreach ($allData as $value) {
            $name[] = $value->getCryptocurrencies()->getName();
            $currency = $value->getCurrency();
        }

        $arrayToString = implode(",", $name);

        $getData = $this->requestApi($arrayToString, $currency);

        foreach ($getData as $key => $value) {

            $cryptocurrencyByName = $entityManager->getRepository(Cryptocurrencies::class)->findOneBy(['name' => strtolower($key)]);

            $cryptocurrency = $entityManager->getRepository(CryptocurrencyData::class)->findBy(['cryptocurrencies' => $cryptocurrencyByName->getId()]);
            
            $cryptocurrency[0]->setCurrency($value[$currency]['TOSYMBOL']);
            $cryptocurrency[0]->setPrice($value[$currency]['PRICE']);
            $cryptocurrency[0]->setMarketCap($value[$currency]['MKTCAP']);
            $cryptocurrency[0]->setVolume24h($value[$currency]['VOLUME24HOURTO']);
            $cryptocurrency[0]->setCirculatingSupply($value[$currency]['SUPPLY']);
            $cryptocurrency[0]->setUpdateAt(new \DateTimeImmutable("now"));
    
            $entityManager->flush();
        }
    }
}
