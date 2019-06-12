<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RentRepository")
 */
class Rent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Canopy", inversedBy="rents")
     * @ORM\JoinColumn(nullable=true)
     */
    private $canopy;

    /**
     * @ORM\Column(type="integer")
     */
    private $count;

    /**
     * @ORM\Column(type="datetime")
     */
    private $rentDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCanopy(): ?Canopy
    {
        return $this->canopy;
    }

    public function setCanopy(Canopy $canopy): self
    {
        $this->canopy = $canopy;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getRentDate(): ?\DateTimeInterface
    {
        return $this->rentDate;
    }

    public function setRentDate(\DateTimeInterface $rentDate): self
    {
        $this->rentDate = $rentDate;

        return $this;
    }

    public function getConvertDate()
    {
        return $this->rentDate->format('d.m.Y');
    }
}
