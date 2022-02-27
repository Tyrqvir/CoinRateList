<?php

declare(strict_types=1);

namespace App\Shared\Entity;

use App\Coin\Repository\CoinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CoinRepository::class)
 * @ORM\Table(
 *     indexes={@ORM\Index(columns={"name"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="coin_unique",columns={"name"})}
 *   )
 */
class Coin
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
     * @ORM\ManyToMany(targetEntity=Currency::class, inversedBy="coins")
     */
    private Collection $currencies;

    public function __construct()
    {
        $this->currencies = new ArrayCollection();
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Currency>
     */
    public function getCurrencies(): Collection
    {
        return $this->currencies;
    }

    public function addCurrency(Currency $currency): self
    {
        if (!$this->currencies->contains($currency)) {
            $this->currencies[] = $currency;
        }

        return $this;
    }

    public function removeCurrency(Currency $currency): self
    {
        $this->currencies->removeElement($currency);

        return $this;
    }

}
