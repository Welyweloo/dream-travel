<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(allowNull=false, message="Obligatoire")
     * @Assert\Range(
     *      min = "1960-01-01",
     *      max = "now",
     *      notInRangeMessage="Date invalide"
     * )
     */

    private $travelDate;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $positiveVote;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $negativeVote;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isReported;

    /**
     * @ORM\Column(type="smallint", options={"default" : 5})
     * @Assert\Choice({"1", "2", "3", "4", "5"})
     * 
     */
    private $rate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="review")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="review", cascade="remove", orphanRemoval=true)
     */
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="reviews")
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity=ReviewLike::class, mappedBy="review",  cascade={"remove"})
     */
    private $likes;

    public function __construct()
    {
        $this->picture = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function __toString()
    {
        if (is_null($this->text)) {
            return 'NULL';
        }
        return $this->text;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTravelDate(): ?\DateTimeInterface
    {
        return $this->travelDate;
    }

    public function setTravelDate(?\DateTimeInterface $travelDate): self
    {
        $this->travelDate = $travelDate;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getPositiveVote(): ?int
    {
        return $this->positiveVote;
    }

    public function setPositiveVote(int $positiveVote): self
    {
        $this->positiveVote = $positiveVote;

        return $this;
    }

    public function getNegativeVote(): ?int
    {
        return $this->negativeVote;
    }

    public function setNegativeVote(int $negativeVote): self
    {
        $this->negativeVote = $negativeVote;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): self
    {
        $this->rate = $rate;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPicture(): Collection
    {
        return $this->picture;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->picture->contains($picture)) {
            $this->picture[] = $picture;
            $picture->setReview($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->picture->contains($picture)) {
            $this->picture->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getReview() === $this) {
                $picture->setReview(null);
            }
        }

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getIsReported(): ?bool
    {
        return $this->isReported;
    }

    public function setIsReported(bool $isReported): self
    {
        $this->isReported = $isReported;

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
            $like->setReview($this);
        }

        return $this;
    }

    public function removeLike(ReviewLike $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getReview() === $this) {
                $like->setReview(null);
            }
        }

        return $this;
    }

    public function isLikedByUser(User $user): bool
    {
        foreach ($this->likes as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }
        return false;
    }
}
