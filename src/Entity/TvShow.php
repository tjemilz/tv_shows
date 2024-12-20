<?php

namespace App\Entity;

use App\Repository\TvShowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: TvShowRepository::class)]
#[Vich\Uploadable]
class TvShow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tvshow', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?OnlineCatalog $onlineCatalog = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column(length: 255)]
    private ?string $director = null;

    #[ORM\Column]
    private ?int $note = null;

    /**
     * @var Collection<int, BestOnes>
     */
    #[ORM\ManyToMany(targetEntity: BestOnes::class, mappedBy: 'tvshows')]
    private Collection $bestOnes;

    #[Vich\UploadableField(mapping: 'tvshows', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;




    public function __construct()
    {
        $this->bestOnes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOnlineCatalog(): ?OnlineCatalog
    {
        return $this->onlineCatalog;
    }

    public function setOnlineCatalog(?OnlineCatalog $onlineCatalog): static
    {
        $this->onlineCatalog = $onlineCatalog;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): static
    {
        $this->director = $director;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return Collection<int, BestOnes>
     */
    public function getBestOnes(): Collection
    {
        return $this->bestOnes;
    }

    public function addBestOne(BestOnes $bestOne): static
    {
        if (!$this->bestOnes->contains($bestOne)) {
            $this->bestOnes->add($bestOne);
            $bestOne->addTvshow($this);
        }

        return $this;
    }

    public function removeBestOne(BestOnes $bestOne): static
    {
        if ($this->bestOnes->removeElement($bestOne)) {
            $bestOne->removeTvshow($this);
        }

        return $this;
    }

    public function __toString() 
    {
        $s = '';
        $s .=  $this->getName() .' ';
        return $s;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }
}
