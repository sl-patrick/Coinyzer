<?php

namespace App\Entity;

use App\Entity\Users;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\CryptocurrenciesRepository;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Types\Boolean;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CryptocurrenciesRepository::class)
 * @Vich\Uploadable
 */
class Cryptocurrencies
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $fullname;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $logo;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @Vich\UploadableField(mapping="cryptocurrencies_logo", fileNameProperty="logo")
     * @var File
     */
    private $logoFile;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Watchlists::class, mappedBy="cryptocurrencies")
     */
    private $watchlists;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $source_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $whitepaper;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @ORM\OneToMany(targetEntity=CryptocurrencyData::class, mappedBy="cryptocurrencies", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $currency_data;

    public function __construct()
    {
        $this->watchlists = new ArrayCollection();
        $this->currency_data = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = strtolower($name);

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = strtolower($fullname);

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo($logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $logoFile
     */
    public function setLogoFile(?File $logo = null): void
    {
        $this->logoFile = $logo;

        if ($logo) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->created_at = new \DateTimeImmutable('now');
        }
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Watchlists[]
     */
    public function getWatchlists(): Collection
    {
        return $this->watchlists;
    }

    public function addWatchlist(Watchlists $watchlist): self
    {
        if (!$this->watchlists->contains($watchlist)) {
            $this->watchlists[] = $watchlist;
            $watchlist->addCryptocurrency($this);
        }

        return $this;
    }

    public function removeWatchlist(Watchlists $watchlist): self
    {
        if ($this->watchlists->removeElement($watchlist)) {
            $watchlist->removeCryptocurrency($this);
        }

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getSourceCode(): ?string
    {
        return $this->source_code;
    }

    public function setSourceCode(?string $source_code): self
    {
        $this->source_code = $source_code;

        return $this;
    }

    public function getWhitepaper(): ?string
    {
        return $this->whitepaper;
    }

    public function setWhitepaper(?string $whitepaper): self
    {
        $this->whitepaper = $whitepaper;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return Collection|CryptocurrencyData[]
     */
    public function getCurrencyData(): Collection
    {
        return $this->currency_data;
    }

    /**
     * Undocumented function
     * @param CryptocurrencyData $currencyData
     * @return self
     */
    public function addCurrencyData(CryptocurrencyData $currencyData): self
    {
        if (!$this->currency_data->contains($currencyData)) {
            $this->currency_data[] = $currencyData;
            $currencyData->setCryptocurrencies($this);
        }

        return $this;
    }

    public function removeCurrencyData(CryptocurrencyData $currencyData): self
    {
        if ($this->currency_data->removeElement($currencyData)) {
            // set the owning side to null (unless already changed)
            if ($currencyData->getCryptocurrencies() === $this) {
                $currencyData->setCryptocurrencies(null);
            }
        }

        return $this;
    }

   
    public function likedByUser(Users $user)
    {
        foreach ($this->watchlists as $watchlist) {

            //Si une watchlist correspond à la watchlist de l'utilisateur.
            if ($watchlist === $user->getWatchlists()) {
                
                return true;
            } else {
                
                return false;
                
            }   
        }
    }
    
}
