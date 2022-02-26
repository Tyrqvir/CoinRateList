<?php

declare(strict_types=1);

namespace App\Shared\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false, unique="true")
     */
    private string $name;

    /**
     * @ORM\ManyToMany(targetEntity=Coin::class, mappedBy="currencies")
     */
    private Collection $coins;

    /**
     * @ORM\OneToMany(targetEntity=Rate::class, mappedBy="currency", orphanRemoval=true)
     */
    private Collection $rates;

    public function __construct()
    {
        $this->coins = new ArrayCollection();
        $this->rates = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Coin>
     */
    public function getCoins(): Collection
    {
        return $this->coins;
    }

    public function addCoin(Coin $coin): self
    {
        if (!$this->coins->contains($coin)) {
            $this->coins[] = $coin;
            $coin->addCurrency($this);
        }

        return $this;
    }

    public function removeCoin(Coin $coin): self
    {
        if ($this->coins->removeElement($coin)) {
            $coin->removeCurrency($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Rate>
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(Rate $rate): self
    {
        if (!$this->rates->contains($rate)) {
            $this->rates[] = $rate;
            $rate->setCurrency($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getCurrency() === $this) {
                $rate->setCurrency(null);
            }
        }

        return $this;
    }


}
