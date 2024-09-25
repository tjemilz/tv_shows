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
    private Collection $tvshow;

    public function __construct()
    {
        $this->tvshow = new ArrayCollection();
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
        return $this->tvshow;
    }

    public function addTvshow(TvShow $tvshow): static
    {
        if (!$this->tvshow->contains($tvshow)) {
            $this->tvshow->add($tvshow);
            $tvshow->setOnlineCatalog($this);
        }

        return $this;
    }

    public function removeTvshow(TvShow $tvshow): static
    {
        if ($this->tvshow->removeElement($tvshow)) {
            // set the owning side to null (unless already changed)
            if ($tvshow->getOnlineCatalog() === $this) {
                $tvshow->setOnlineCatalog(null);
            }
        }

        return $this;
    }
}
