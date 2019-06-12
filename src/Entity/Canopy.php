<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CanopyRepository")
 */
class Canopy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $main;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reserv;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $aad;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $rig;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $pack_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rent", mappedBy="canopy")
     */
    private $rents;

    public function __construct()
    {
        $this->rents = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getRents()
    {
        return $this->rents;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getMain(): ?string
    {
        return $this->main;
    }

    public function setMain(string $main): self
    {
        $this->main = $main;

        return $this;
    }

    public function getReserv(): ?string
    {
        return $this->reserv;
    }

    public function setReserv(?string $reserv): self
    {
        $this->reserv = $reserv;

        return $this;
    }

    public function getAad(): ?string
    {
        return $this->aad;
    }

    public function setAad(?string $aad): self
    {
        $this->aad = $aad;

        return $this;
    }

    public function getRig(): ?string
    {
        return $this->rig;
    }

    public function setRig(?string $rig): self
    {
        $this->rig = $rig;

        return $this;
    }

    public function getPackDate(): ?\DateTimeInterface
    {
        return $this->pack_date;
    }

    public function setPackDate(?\DateTimeInterface $pack_date): self
    {
        $this->pack_date = $pack_date;

        return $this;
    }

    public function getName()
    {
        return $this->number . ' ' . $this->main;
    }

    public function getConvertDate()
    {
        return $this->pack_date ? $this->packDate->format('d.m.Y') : '';
    }
}
