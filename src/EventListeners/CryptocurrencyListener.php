<?php

namespace App\EventListeners;

use App\Entity\Cryptocurrencies;
use App\Service\CallApi;
use Doctrine\Persistence\Event\LifecycleEventArgs;

Class CryptocurrencyListener
{
    private $api;

    public function __construct(CallApi $callApi)
    {
        $this->api = $callApi; 
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

            $logo = $this->api->fetchLogo(strtoupper($name));
            $data = $this->api->fetchCryptocurrencyData(strtoupper($name));

            $entity->setLogo($logo);
            $entity->setCreatedAt(new \DateTime());
            $entity->addCurrencyData($data);
            
        }
    }
}