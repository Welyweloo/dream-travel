<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Ce compte existe déjà")
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(allowNull=false, message="Prénom obligatoire")
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "{{ limit }} caractères minimum",
     *      maxMessage = "{{ limit }} caractères maximum",
     *      allowEmptyString = false
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(allowNull=false, message="Nom obligatoire")
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "{{ limit }} caractères minimum",
     *      maxMessage = "{{ limit }} caractères maximum",
     *      allowEmptyString = false
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "{{ limit }} caractères minimum",
     *      maxMessage = "{{ limit }} caractères maximum",
     *      allowEmptyString = false
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(allowNull=false, message="Date de naissance obligatoire")
     * @Assert\LessThan("-18 years", message="Majorité obligatoire (18 ans)")
     * 
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     * @Assert\NotBlank(allowNull=false, message="Email obligatoire")
     * @Assert\Email(
     * message = "Email non valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(allowNull=false, message="Mot de passe non renseigné")
     * @Assert\Regex(
     *  pattern = "#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#",
     *  match=true,
     *  message="8 caractères minimum")
     * @Assert\Length(
     *  min = 8,
     *  minMessage="8 caractères minimum")
     */

    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 0})
     */
    private $points;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;


    /**
     * @ORM\ManyToMany(targetEntity=Language::class, inversedBy="users")
     */
    private $language;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="user", cascade="remove", orphanRemoval=true)
     */
    private $review;

    /**
     * @ORM\ManyToMany(targetEntity=Badge::class, inversedBy="users")
     */
    private $badge;

    /**
     * @ORM\ManyToMany(targetEntity=CityList::class, inversedBy="users")
     */
    private $cityList;

    /**
     * @ORM\ManyToMany(targetEntity=City::class, inversedBy="users")
     */
    private $city;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="users")
     */
    private $favoriteUser;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favoriteUser")
     */
    private $users;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=ReviewLike::class, mappedBy="user")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=CityLike::class, mappedBy="user")
     */
    private $cityLikes;


    public function __construct()
    {
        $this->language = new ArrayCollection();
        $this->review = new ArrayCollection();
        $this->badge = new ArrayCollection();
        $this->cityList = new ArrayCollection();
        $this->city = new ArrayCollection();
        $this->favoriteUser = new ArrayCollection();
        $this->users = new ArrayCollection();
        //$this->username = '#'.random_int(1, 100000);
        $this->createdAt = new \DateTime;
        $this->likes = new ArrayCollection();
        $this->cityLikes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
        
        if($this->getIsActive())
        {
            // guarantee every user at least has ROLE_USER
            $roles[] = 'ROLE_USER';
        }

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

    public function setPassword(?string $password): self
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        //$this->username = $this->firstname.'-'.$this->name.'#'.random_int(1, 100000);

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Language[]
     */
    public function getLanguage(): Collection
    {
        return $this->language;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->language->contains($language)) {
            $this->language[] = $language;
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        if ($this->language->contains($language)) {
            $this->language->removeElement($language);
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReview(): Collection
    {
        return $this->review;
    }

    public function addReview(Review $review): self
    {
        if (!$this->review->contains($review)) {
            $this->review[] = $review;
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->review->contains($review)) {
            $this->review->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Badge[]
     */
    public function getBadge(): Collection
    {
        return $this->badge;
    }

    public function addBadge(Badge $badge): self
    {
        if (!$this->badge->contains($badge)) {
            $this->badge[] = $badge;
        }

        return $this;
    }

    public function removeBadge(Badge $badge): self
    {
        if ($this->badge->contains($badge)) {
            $this->badge->removeElement($badge);
        }

        return $this;
    }

    /**
     * @return Collection|CityList[]
     */
    public function getCityList(): Collection
    {
        return $this->cityList;
    }

    public function addCityList(CityList $cityList): self
    {
        if (!$this->cityList->contains($cityList)) {
            $this->cityList[] = $cityList;
        }

        return $this;
    }

    public function removeCityList(CityList $cityList): self
    {
        if ($this->cityList->contains($cityList)) {
            $this->cityList->removeElement($cityList);
        }

        return $this;
    }

    /**
     * @return Collection|City[]
     */
    public function getCity(): Collection
    {
        return $this->city;
    }

    public function addCity(City $city): self
    {
        if (!$this->city->contains($city)) {
            $this->city[] = $city;
        }

        return $this;
    }

    public function removeCity(City $city): self
    {
        if ($this->city->contains($city)) {
            $this->city->removeElement($city);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavoriteUser(): Collection
    {
        return $this->favoriteUser;
    }

    public function addFavoriteUser(User $favoriteUser): self
    {
        if (!$this->favoriteUser->contains($favoriteUser)) {
            $this->favoriteUser[] = $favoriteUser;
        }

        return $this;
    }

    public function removeFavoriteUser(User $favoriteUser): self
    {
        if ($this->favoriteUser->contains($favoriteUser)) {
            $this->favoriteUser->removeElement($favoriteUser);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addFavoriteUser($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeFavoriteUser($this);
        }

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|ReviewLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(ReviewLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(ReviewLike $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }
          

    public function isFavoriteUser(User $userTarget) :bool
    {
        foreach ($this->users as $userLike) {
            if ($userLike->getFavoriteUser() === $userTarget) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Collection|CityLike[]
     */
    public function getCityLikes(): Collection
    {
        return $this->cityLikes;
    }

    public function addCityLike(CityLike $cityLike): self
    {
        if (!$this->cityLikes->contains($cityLike)) {
            $this->cityLikes[] = $cityLike;
            $cityLike->setUser($this);
        }

        return $this;
    }

    public function removeCityLike(CityLike $cityLike): self
    {
        if ($this->cityLikes->contains($cityLike)) {
            $this->cityLikes->removeElement($cityLike);
            // set the owning side to null (unless already changed)
            if ($cityLike->getUser() === $this) {
                $cityLike->setUser(null);
            }
        }

        return $this;
    }
}
