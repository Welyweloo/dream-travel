<?php

namespace App\Entity;

use App\Repository\WeatherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WeatherRepository::class)
 */
class Weather
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $month;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $averageMin;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $averageMax;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $tempLevelMin;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $tempLevelMax;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="weather")
     */
    private $city;

    public function __toString()
    {
        if(is_null($this->month)) {
            return 'NULL';
        }
        return $this->month;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;

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

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getAverageMin(): ?string
    {
        return $this->averageMin;
    }

    public function setAverageMin(?string $averageMin): self
    {
        $this->averageMin = $averageMin;

        return $this;
    }

    public function getAverageMax(): ?string
    {
        return $this->averageMax;
    }

    public function setAverageMax(?string $averageMax): self
    {
        $this->averageMax = $averageMax;

        return $this;
    }

    public function getTempLevelMin(): ?string
    {
        return $this->tempLevelMin;
    }

    public function setTempLevelMin(?string $tempLevelMin): self
    {
        $this->tempLevelMin = $tempLevelMin;

        return $this;
    }

    public function getTempLevelMax(): ?string
    {
        return $this->tempLevelMax;
    }

    public function setTempLevelMax(?string $tempLevelMax): self
    {
        $this->tempLevelMax = $tempLevelMax;

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
}
