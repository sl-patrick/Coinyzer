<?php

namespace App\EventListeners;

use App\Entity\Cryptocurrencies;
use App\Service\CallApi;
use Doctrine\Persistence\Event\LifecycleEventArgs;

Class CryptocurrencyListener
{
    private $_api;

    public function __construct(CallApi $callApi)
    {
        $this->_api = $callApi; 
    }

    /**
     * Récupère les données de la cryptomonnaie avant l'enregistrement en base de donnée.
     *
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Cryptocurrencies) {

            return;

        } elseif ($entity instanceof Cryptocurrencies) {

            $name = $entity->getName();

            $data = $this->api->fetchCryptocurrencyData(strtoupper($name));

            $entity->addCurrencyData($data);
            
        }
    }
}