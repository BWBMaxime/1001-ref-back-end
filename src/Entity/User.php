<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Address;
use App\Entity\Account;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $biography;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyLogo;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkedin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyPicture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyType;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="subscribers")
     */
    private $favorites;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favorites")
     */
    private $subscribers;

    /**
     * @ORM\OneToOne(targetEntity=Account::class, mappedBy="account", cascade={"persist", "remove"})
     */
    private $account;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="owner")
     */
    private $products;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    public function __construct()
    {
        $this->favorites = new ArrayCollection();
        $this->subscribers = new ArrayCollection();
        $this->products = new ArrayCollection();
    }


    public function hydrate($form)
    {
        if(isset($form["name"]))$this->setName($form["name"]);
        if(isset($form["firstname"]))$this->setFirstname($form["firstname"]);
        if(isset($form["company"]))$this->setCompany($form["company"]);
        if(isset($form["phone"]))$this->setPhone($form["phone"]);
        if(isset($form["mail"]))$this->setMail($form["mail"]);
        if(isset($form["password"]))$this->setPassword($form["password"]);
        if(isset($form["siret"]))$this->setSiret($form["siret"]);
        if(isset($form["biography"]))$this->setBiography($form["biography"]);
        if(isset($form["companyLogo"]))$this->setCompanyLogo($form["companyLogo"]);

        if(isset($form['address'])){

            $address = $this->getAddress();
            if($address == null) $address = new Address();

            $address->setNumber($form['address']['number']);
            $address->setRoad($form['address']['road']);
            $address->setCity($form['address']['city']);
            $address->setZipcode($form['address']['zipcode']);
            $address->setCountry($form['address']['country']);
            
            $this->setAddress($address);
        }

        if(isset($form["facebook"]))$this->setFacebook($form["facebook"]);
        if(isset($form["linkedin"]))$this->setLinkedin($form["linkedin"]);
        if(isset($form["website"]))$this->setWebsite($form["website"]);
        if(isset($form["companyPicture"]))$this->setCompanyPicture($form["companyPicture"]);
        if(isset($form["companyType"]))$this->setCompanyType($form["companyType"]);
        if(isset($form["role"]))$this->setRole($form["role"]);

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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(string $biography): self
    {
        $this->biography = $biography;

        return $this;
    }

    public function getCompanyLogo(): ?string
    {
        return $this->companyLogo;
    }

    public function setCompanyLogo(string $companyLogo): self
    {
        $this->companyLogo = $companyLogo;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getCompanyPicture()
    {
        return $this->companyPicture;
    }

    public function setCompanyPicture($companyPicture): self
    {
        $this->companyPicture = $companyPicture;

        return $this;
    }

    public function getCompanyType(): ?string
    {
        return $this->companyType;
    }

    public function setCompanyType(string $companyType): self
    {
        $this->companyType = $companyType;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(self $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(self $favorite): self
    {
        $this->favorites->removeElement($favorite);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSubscribers(): Collection
    {
        return $this->subscribers;
    }

    public function addSubscriber(self $subscriber): self
    {
        if (!$this->subscribers->contains($subscriber)) {
            $this->subscribers[] = $subscriber;
            $subscriber->addFavorite($this);
        }

        return $this;
    }

    public function removeSubscriber(self $subscriber): self
    {
        if ($this->subscribers->removeElement($subscriber)) {
            $subscriber->removeFavorite($this);
        }

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        // set the owning side of the relation if necessary
        if ($account->getAccount() !== $this) {
            $account->setAccount($this);
        }

        $this->account = $account;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setOwner($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getOwner() === $this) {
                $product->setOwner(null);
            }
        }

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
