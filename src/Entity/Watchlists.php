<?php

namespace App\Entity;

use App\Entity\Users;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\WatchlistsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=WatchlistsRepository::class)
 */
class Watchlists
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Users::class, inversedBy="watchlists", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=Cryptocurrencies::class, inversedBy="watchlists")
     */
    private $cryptocurrencies;

    public function __construct()
    {
        $this->cryptocurrencies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection|Cryptocurrencies[]
     */
    public function getCryptocurrencies(): Collection
    {
        return $this->cryptocurrencies;
    }

    public function addCryptocurrency(Cryptocurrencies $cryptocurrency): self
    {
        if (!$this->cryptocurrencies->contains($cryptocurrency)) {
            $this->cryptocurrencies[] = $cryptocurrency;
        }

        return $this;
    }

    public function removeCryptocurrency(Cryptocurrencies $cryptocurrency): self
    {
        $this->cryptocurrencies->removeElement($cryptocurrency);

        return $this;
    }
}
