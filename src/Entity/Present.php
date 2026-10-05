<?php

namespace App\Entity;

use App\Repository\PresentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresentRepository::class)]
class Present
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'presents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Jeux $jeux = null;

    #[ORM\ManyToOne(inversedBy: 'presents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plateforme $plateforme = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getJeux(): ?Jeux
    {
        return $this->jeux;
    }

    public function setJeux(?Jeux $jeux): static
    {
        $this->jeux = $jeux;

        return $this;
    }

    public function getPlateforme(): ?Plateforme
    {
        return $this->plateforme;
    }

    public function setPlateforme(?Plateforme $plateforme): static
    {
        $this->plateforme = $plateforme;

        return $this;
    }
}
