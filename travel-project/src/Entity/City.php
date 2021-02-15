<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"api_v1_city"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"api_v1_city"})
     */
    private $cityName;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"api_v1_city"})
     */
    private $countryName;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"api_v1_city"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="city")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="city")
     */
    private $reviews;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="cities")
     */
    private $tag;

    /**
     * @ORM\OneToMany(targetEntity=Weather::class, mappedBy="city")
     *  
     */
    private $weather;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"api_v1_city"})
     */
    private $geonameId;

    /**
     * @ORM\ManyToMany(targetEntity=CityList::class, inversedBy="cities")
     */
    private $cityLists;

    /**
     * @ORM\OneToMany(targetEntity=CityLike::class, mappedBy="city")
     */
    private $cityLikes;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->tag = new ArrayCollection();
        $this->createdAt = new \DateTime;
        $this->weather = new ArrayCollection();
        $this->cityLists = new ArrayCollection();
        $this->cityLikes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->cityName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    public function setCityName(string $cityName): self
    {
        $this->cityName = $cityName;

        return $this;
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function setCountryName(string $countryName): self
    {
        $this->countryName = $countryName;

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
            $user->addCity($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeCity($this);
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setCity($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getCity() === $this) {
                $review->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return Collection|Weather[]
     */
    public function getWeather(): Collection
    {
        return $this->weather;
    }

    public function addWeather(Weather $weather): self
    {
        if (!$this->weather->contains($weather)) {
            $this->weather[] = $weather;
            $weather->setCity($this);
        }

        return $this;
    }

    public function removeWeather(Weather $weather): self
    {
        if ($this->weather->contains($weather)) {
            $this->weather->removeElement($weather);
            // set the owning side to null (unless already changed)
            if ($weather->getCity() === $this) {
                $weather->setCity(null);
            }
        }

        return $this;
    }

    public function getGeonameId(): ?int
    {
        return $this->geonameId;
    }

    public function setGeonameId(int $geonameId): self
    {
        $this->geonameId = $geonameId;

        return $this;
    }

    /**
     * @Groups({"api_v1_city"})
     */
    public function getUrlCity()
    {
        return '/city/' . $this->getGeonameId();
        //return 'http://' . $_SERVER['SERVER_NAME'] . '/city/' . $this->getGeonameId();
    }

    /**
     * @return Collection|CityList[]
     */
    public function getCityLists(): Collection
    {
        return $this->cityLists;
    }

    public function addCityList(CityList $cityList): self
    {
        if (!$this->cityLists->contains($cityList)) {
            $this->cityLists[] = $cityList;
            $cityList->addCity($this);
        }

        return $this;
    }

    public function removeCityList(CityList $cityList): self
    {
        if ($this->cityLists->contains($cityList)) {
            $this->cityLists->removeElement($cityList);
            $cityList->removeCity($this);
        }

        return $this;
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
            $cityLike->setCity($this);
        }

        return $this;
    }

    public function removeCityLike(CityLike $cityLike): self
    {
        if ($this->cityLikes->contains($cityLike)) {
            $this->cityLikes->removeElement($cityLike);
            // set the owning side to null (unless already changed)
            if ($cityLike->getCity() === $this) {
                $cityLike->setCity(null);
            }
        }

        return $this;
    }


    public function isLikedByUser(User $user): bool
    {
        foreach ($this->cityLikes as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }
        return false;
    }
}
