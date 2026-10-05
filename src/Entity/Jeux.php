<?php

namespace App\Entity;

use App\Repository\JeuxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JeuxRepository::class)]
class Jeux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $date_sortie = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    /** @var list<string> */
    #[ORM\Column(type: Types::JSON)]
    private array $galerie = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $youtubeUrl = null;

    #[ORM\ManyToOne(inversedBy: 'jeux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Genre $genre = null;

    /**
     * @var Collection<int, Note>
     */
    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'jeux')]
    private Collection $notes;

    /**
     * @var Collection<int, Present>
     */
    #[ORM\OneToMany(targetEntity: Present::class, mappedBy: 'jeux')]
    private Collection $presents;

    /**
     * @var Collection<int, Avis>
     */
    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'jeux')]
    private Collection $avis;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->presents = new ArrayCollection();
        $this->avis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateSortie(): ?\DateTime
    {
        return $this->date_sortie;
    }

    public function setDateSortie(?\DateTime $date_sortie): static
    {
        $this->date_sortie = $date_sortie;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): static
    {
        $this->detail = $detail;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): static
    {
        $this->img = $img;

        return $this;
    }

    /** @return list<string> */
    public function getGalerie(): array
    {
        return $this->galerie;
    }

    /** @param list<string> $galerie */
    public function setGalerie(array $galerie): static
    {
        $this->galerie = $galerie;

        return $this;
    }

    public function getYoutubeUrl(): ?string
    {
        return $this->youtubeUrl;
    }

    public function setYoutubeUrl(?string $youtubeUrl): static
    {
        $this->youtubeUrl = $youtubeUrl;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setJeux($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getJeux() === $this) {
                $note->setJeux(null);
            }
        }

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
            $present->setJeux($this);
        }

        return $this;
    }

    public function removePresent(Present $present): static
    {
        if ($this->presents->removeElement($present)) {
            // set the owning side to null (unless already changed)
            if ($present->getJeux() === $this) {
                $present->setJeux(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setJeux($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getJeux() === $this) {
                $avi->setJeux(null);
            }
        }

        return $this;
    }
}
