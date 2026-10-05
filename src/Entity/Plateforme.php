<?php

namespace App\Entity;

use App\Repository\PlateformeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlateformeRepository::class)]
class Plateforme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Present>
     */
    #[ORM\OneToMany(targetEntity: Present::class, mappedBy: 'plateforme')]
    private Collection $presents;

    public function __construct()
    {
        $this->presents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Present>
     */
    public function getPresents(): Collection
    {
        return $this->presents;
    }

    public function addPresent(Present $present): static
    {
        if (!$this->presents->contains($present)) {
            $this->presents->add($present);
            $present->setPlateforme($this);
        }

        return $this;
    }

    public function removePresent(Present $present): static
    {
        if ($this->presents->removeElement($present)) {
            // set the owning side to null (unless already changed)
            if ($present->getPlateforme() === $this) {
                $present->setPlateforme(null);
            }
        }

        return $this;
    }
}
