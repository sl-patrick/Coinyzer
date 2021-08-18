<?php

namespace App\Entity;

use App\Repository\CryptocurrencyDataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CryptocurrencyDataRepository::class)
 */
class CryptocurrencyData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $currency;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="decimal", precision=17, scale=2)
     */
    private $market_cap;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $volume_24h;

    /**
     * @ORM\Column(type="integer")
     */
    private $circulating_supply;

    /**
     * @ORM\ManyToOne(targetEntity=Cryptocurrencies::class, inversedBy="currency_data")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cryptocurrencies;

    /**
     * @ORM\Column(type="datetime")
     */
    private $update_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastUpdateAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getMarketCap(): ?string
    {
        return $this->market_cap;
    }

    public function setMarketCap(string $market_cap): self
    {
        $this->market_cap = $market_cap;

        return $this;
    }

    public function getVolume24h(): ?string
    {
        return $this->volume_24h;
    }

    public function setVolume24h(string $volume_24h): self
    {
        $this->volume_24h = $volume_24h;

        return $this;
    }

    public function getCirculatingSupply(): ?int
    {
        return $this->circulating_supply;
    }

    public function setCirculatingSupply(int $circulating_supply): self
    {
        $this->circulating_supply = $circulating_supply;

        return $this;
    }

    public function getCryptocurrencies(): ?Cryptocurrencies
    {
        return $this->cryptocurrencies;
    }

    public function setCryptocurrencies(?Cryptocurrencies $cryptocurrencies): self
    {
        $this->cryptocurrencies = $cryptocurrencies;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->update_at;
    }

    public function setUpdateAt(\DateTimeInterface $update_at): self
    {
        $this->update_at = $update_at;

        return $this;
    }

    public function getLastUpdateAt(): ?\DateTimeInterface
    {
        return $this->lastUpdateAt;
    }

    public function setLastUpdateAt(?\DateTimeInterface $lastUpdateAt): self
    {
        $this->lastUpdateAt = $lastUpdateAt;

        return $this;
    }

    
}
