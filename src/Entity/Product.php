<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $description;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="boolean")
     */
    private $new;

    /**
     * @ORM\OneToMany(targetEntity=Variation::class, mappedBy="product", orphanRemoval=true)
     */
    private $variations;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class,cascade={"persist"})
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    public function __construct()
    {
        $this->variations = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getNew(): ?bool
    {
        return $this->new;
    }

    public function setNew(bool $new): self
    {
        $this->new = $new;

        return $this;
    }

    /**
     * @return Collection|Variation[]
     */
    public function getVariations(): Collection
    {
        return $this->variations;
    }

    public function addVariation(Variation $variation): self
    {
        if (!$this->variations->contains($variation)) {
            $this->variations[] = $variation;
            $variation->setProduct($this);
        }

        return $this;
    }

    public function removeVariation(Variation $variation): self
    {
        if ($this->variations->removeElement($variation)) {
            // set the owning side to null (unless already changed)
            if ($variation->getProduct() === $this) {
                $variation->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tags[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }


                // public function clearTag(): self
                // {
                //     $this->tags = [] ;

                //     return $this;
                // }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Hydrates the product with a form
     */
    public function hydrate($data, $doctrine){

        $this->setName($data['name']);
        $this->setCategory($data['category']);
        $this->setDescription($data['description']);
        $this->setActive(false);
        $this->setNew(true);

        foreach ($data['variations'] as $variation) 
        {

            $newVariation = new Variation();

            $newVariation->setProduct($this);
            $newVariation->setContainer($variation['contenant']);
            $newVariation->setConditioning($variation['conditionnement']);
            $newVariation->setCapacity($variation['contenance']);
            $newVariation->setDealerPrice($variation['prixRevendeur']);
            $newVariation->setRestaurateurPrice($variation['prixRestaurateur']);
            $this->addVariation($newVariation);
        }

        foreach ($data['tags'] as $tag) 
        {
            $newTag = $doctrine->getRepository(Tags::class)->findOneBy(['name'=>$tag]);
            if($newTag == null){
                $newTag = new Tags();
                $newTag->setName($tag);
            }
            $this->addTag($newTag);
        }

        
        $owner = $doctrine->getRepository(User::class)->findOneBy(['id'=>$data['userId']]);
        $this->setOwner($owner);
    }

}
