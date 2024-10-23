<?php

namespace App\Entity;

use App\Repository\OnlineCatalogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OnlineCatalogRepository::class)]
class OnlineCatalog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, TvShow>
     */
    #[ORM\OneToMany(targetEntity: TvShow::class, mappedBy: 'onlineCatalog', orphanRemoval: true)]
    private Collection $tvshows;

    #[ORM\Column(length: 255)]
    private ?string $owner = null;

    public function __construct()
    {
        $this->tvshows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, TvShow>
     */
    public function getTvshow(): Collection
    {
        return $this->tvshows;
    }

    public function addTvshow(TvShow $tvshows): static
    {
        if (!$this->tvshows->contains($tvshows)) {
            $this->tvshows->add($tvshows);
            $tvshows->setOnlineCatalog($this);
        }

        return $this;
    }

    public function removeTvshow(TvShow $tvshows): static
    {
        if ($this->tvshows->removeElement($tvshows)) {
            // set the owning side to null (unless already changed)
            if ($tvshows->getOnlineCatalog() === $this) {
                $tvshows->setOnlineCatalog(null);
            }
        }

        return $this;
    }


    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
