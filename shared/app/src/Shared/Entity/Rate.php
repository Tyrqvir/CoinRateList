<?php

declare(strict_types=1);

namespace App\Shared\Entity;

use App\Rate\Repository\RateRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=RateRepository::class)
 * @ORM\Table(indexes={@ORM\Index(columns={"create_at"})})
 */
class Rate
{
    public const CACHE_TAG = 'rate';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="decimal", precision=27, scale=8)
     */
    private float $amount;

    /**
     * @ORM\Column(type="bigint")
     */
    private int $createAt;

    /**
     * @ORM\ManyToOne(targetEntity=Currency::class, inversedBy="rates")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Currency $currency;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreateAt(): int
    {
        return $this->createAt;
    }

    public function setCreateAt(int $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
