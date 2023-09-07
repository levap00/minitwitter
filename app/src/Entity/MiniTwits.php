<?php

namespace App\Entity;

use App\Repository\MiniTwitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MiniTwitsRepository::class)]
class MiniTwits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column]
    private ?bool $isPublic = null;

    #[ORM\ManyToOne(inversedBy: 'miniTwits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'miniTwits')]
    private ?self $miniTwit = null;

    #[ORM\OneToMany(mappedBy: 'miniTwit', targetEntity: self::class)]
    private Collection $miniTwits;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'liked')]
    private Collection $likedBy;

    public function __construct()
    {
        $this->miniTwits = new ArrayCollection();
        $this->likedBy = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }


    public function getMiniTwit(): ?self
    {
        return $this->miniTwit;
    }

    public function setMiniTwit(?self $miniTwit): self
    {
        $this->miniTwit = $miniTwit;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getMiniTwits(): Collection
    {
        return $this->miniTwits;
    }

    public function addMiniTwit(self $miniTwit): self
    {
        if (!$this->miniTwits->contains($miniTwit)) {
            $this->miniTwits->add($miniTwit);
            $miniTwit->setMiniTwit($this);
        }

        return $this;
    }

    public function removeMiniTwit(self $miniTwit): self
    {
        if ($this->miniTwits->removeElement($miniTwit)) {
            // set the owning side to null (unless already changed)
            if ($miniTwit->getMiniTwit() === $this) {
                $miniTwit->setMiniTwit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLikedBy(): Collection
    {
        return $this->likedBy;
    }

    public function addLikedBy(User $likedBy): self
    {
        if (!$this->likedBy->contains($likedBy)) {
            $this->likedBy->add($likedBy);
            $likedBy->addLiked($this);
        }

        return $this;
    }

    public function removeLikedBy(User $likedBy): self
    {
        if ($this->likedBy->removeElement($likedBy)) {
            $likedBy->removeLiked($this);
        }

        return $this;
    }
}
