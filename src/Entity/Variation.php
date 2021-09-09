<?php

namespace App\Entity;

use App\Repository\VariationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VariationRepository::class)
 */
class Variation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="variations",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dealerPrice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $restaurateurPrice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $capacity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $conditioning;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $container;

    /**
     * @ORM\ManyToOne(targetEntity=Sale::class, inversedBy="variations")
     */
    private $sale;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getDealerPrice(): ?string
    {
        return $this->dealerPrice;
    }

    public function setDealerPrice(string $dealerPrice): self
    {
        $this->dealerPrice = $dealerPrice;

        return $this;
    }

    public function getRestaurateurPrice(): ?string
    {
        return $this->restaurateurPrice;
    }

    public function setRestaurateurPrice(string $restaurateurPrice): self
    {
        $this->restaurateurPrice = $restaurateurPrice;

        return $this;
    }

    public function getCapacity(): ?string
    {
        return $this->capacity;
    }

    public function setCapacity(string $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getConditioning(): ?string
    {
        return $this->conditioning;
    }

    public function setConditioning(string $conditioning): self
    {
        $this->conditioning = $conditioning;

        return $this;
    }

    public function getContainer(): ?string
    {
        return $this->container;
    }

    public function setContainer(string $container): self
    {
        $this->container = $container;

        return $this;
    }

    public function getSale(): ?Sale
    {
        return $this->sale;
    }

    public function setSale(?Sale $sale): self
    {
        $this->sale = $sale;

        return $this;
    }
}
