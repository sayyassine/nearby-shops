<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apiToken;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Store")
     */
    private $liked_stores;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StoreDislike", mappedBy="user", orphanRemoval=true , cascade={"persist" , "remove"})
     */
    private $disliked_stores;

    public function __construct()
    {
        $this->liked_stores = new ArrayCollection();
        $this->disliked_stores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * @return Collection|Store[]
     */
    public function getLikedStores(): Collection
    {
        return $this->liked_stores;
    }

    public function addLikedStore(Store $likedStore): self
    {
        if (!$this->liked_stores->contains($likedStore)) {
            $this->liked_stores[] = $likedStore;
        }

        return $this;
    }

    public function removeLikedStore(Store $likedStore): self
    {
        if ($this->liked_stores->contains($likedStore)) {
            $this->liked_stores->removeElement($likedStore);
        }

        return $this;
    }

    /**
     * @return Collection|StoreDislike[]
     */
    public function getDislikedStores(): Collection
    {
        return $this->disliked_stores;
    }

    public function addDislikedStore(StoreDislike $dislikedStore): self
    {
        if (!$this->disliked_stores->contains($dislikedStore) && !$this->hasDisliked($dislikedStore->getStore())) {
            $this->disliked_stores[] = $dislikedStore;
            $dislikedStore->setUser($this);
            $dislikedStore->setDislikeDate(new \DateTimeImmutable());
        }

        return $this;
    }

    public function removeDislikedStore(StoreDislike $dislikedStore): self
    {
        if ($this->disliked_stores->contains($dislikedStore)) {
            $this->disliked_stores->removeElement($dislikedStore);
            // set the owning side to null (unless already changed)
            if ($dislikedStore->getUser() === $this) {
                $dislikedStore->setUser(null);
            }
        }

        return $this;
    }

    /**
     * checks if the store is in the dislike list.
     * performing a simple check on the dislikes list since $disliked_store is a collection of StoreDislike Objects.
     * Which are used to to store the time of the dislike so it can be removed after a defined amount of time.
     **/
    public function hasDisliked(Store $store){
        return $this->disliked_stores->exists(function($key , $element) use ($store) { return $store->getId() === $element->getId() ;} );
    }

    public function getDislike(Store $store){
        $res = $this->disliked_stores->filter(function ($element) use ($store) {return $element->getId() === $store->getId();}) ;
        return empty($res) ? null : $res[0] ;
    }

    public function removeDislike(Store $store){

        $dislike = $this->getDislike($store);
        if($store)
            $this->removeDislikedStore($dislike);
    }
}
