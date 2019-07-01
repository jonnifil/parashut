<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CanopyImageRepository")
 */
class CanopyImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Canopy", inversedBy="images")
     * @ORM\JoinColumn(nullable=true)
     */
    private $canopy;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $file;

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

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }
}
