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



    #[ORM\OneToOne(mappedBy: 'catalog', cascade: ['persist', 'remove'])]
    private ?Member $member = null;

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



    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): static
    {
        // unset the owning side of the relation if necessary
        if ($member === null && $this->member !== null) {
            $this->member->setCatalog(null);
        }

        // set the owning side of the relation if necessary
        if ($member !== null && $member->getCatalog() !== $this) {
            $member->setCatalog($this);
        }

        $this->member = $member;

        return $this;
    }
}
