<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{

    /**
     * Début de chemin vers les images
     */
    private const CHEMIN_IMAGE = "https://i.ytimg.com/vi/";
        
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\DateTime]
    #[Assert\LessThan("today", message: "La date de publication ne peut pas être dans le futur.")]
    private ?\DateTimeInterface $publishedAt = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $videoId = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    private ?Playlist $playlist = null;

    /**
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'formations')]
    private Collection $categories;

    /**
     * 
     * @param 
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * 
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * 
     * @return \DateTimeInterface|null
     */
    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    /**
     * 
     * @param \DateTimeInterface|null $publishedAt
     * @return static
     */
    public function setPublishedAt(?\DateTimeInterface $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getPublishedAtString(): string {
        if($this->publishedAt == null){
            return "";
        }
        return $this->publishedAt->format('d/m/Y');     
    }      
    
    /**
     * 
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * 
     * @param string|null $title
     * @return static
     */
    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * 
     * @param string|null $description
     * @return static
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    /**
     * 
     * @param string|null $videoId
     * @return static
     */
    public function setVideoId(?string $videoId): static
    {
        $this->videoId = $videoId;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getMiniature(): ?string
    {
        return self::CHEMIN_IMAGE.$this->videoId."/default.jpg";
    }

    /**
     * 
     * @return string|null
     */
    public function getPicture(): ?string
    {
        return self::CHEMIN_IMAGE.$this->videoId."/hqdefault.jpg";
    }
    
    /**
     * 
     * @return playlist|null
     */
    public function getPlaylist(): ?playlist
    {
        return $this->playlist;
    }

    /**
     * 
     * @param Playlist|null $playlist
     * @return static
     */
    public function setPlaylist(?Playlist $playlist): static
    {
        $this->playlist = $playlist;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * 
     * @param Categorie $category
     * @return static
     */
    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    /**
     * 
     * @param Categorie $category
     * @return static
     */
    public function removeCategory(Categorie $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }
}
