<?php

namespace App\Entity;

use App\Repository\BestOnesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BestOnesRepository::class)]
class BestOnes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $published = null;

    #[ORM\ManyToOne(inversedBy: 'bestOnes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Member $creator = null;

    /**
     * @var Collection<int, TvShow>
     */
    #[ORM\ManyToMany(targetEntity: TvShow::class, inversedBy: 'bestOnes')]
    private Collection $tvshows;

    public function __construct()
    {
        $this->tvshows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getCreator(): ?Member
    {
        return $this->creator;
    }

    public function setCreator(?Member $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection<int, TvShow>
     */
    public function getTvshows(): Collection
    {
        return $this->tvshows;
    }

    public function addTvshow(TvShow $tvshow): static
    {
        if (!$this->tvshows->contains($tvshow)) {
            $this->tvshows->add($tvshow);
        }

        return $this;
    }

    public function removeTvshow(TvShow $tvshow): static
    {
        $this->tvshows->removeElement($tvshow);

        return $this;
    }
}
