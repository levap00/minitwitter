<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: MiniTwits::class)]
    private Collection $miniTwits;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserProfile $userProfile = null;

    #[ORM\ManyToMany(targetEntity: MiniTwits::class, inversedBy: 'likedBy')]
    private Collection $liked;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'followed')]
    #[ORM\JoinTable('followers')]
    private Collection $follows;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'follows')]
    private Collection $followed;


    public function __construct()
    {
        $this->miniTwits = new ArrayCollection();
        $this->liked = new ArrayCollection();
        $this->follows = new ArrayCollection();
        $this->followed = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, MiniTwits>
     */
    public function getMiniTwits(): Collection
    {
        return $this->miniTwits;
    }

    public function addMiniTwit(MiniTwits $miniTwit): self
    {
        if (!$this->miniTwits->contains($miniTwit)) {
            $this->miniTwits->add($miniTwit);
            $miniTwit->setAuthor($this);
        }

        return $this;
    }

    public function removeMiniTwit(MiniTwits $miniTwit): self
    {
        if ($this->miniTwits->removeElement($miniTwit)) {
            // set the owning side to null (unless already changed)
            if ($miniTwit->getAuthor() === $this) {
                $miniTwit->setAuthor(null);
            }
        }

        return $this;
    }

    public function getUserProfile(): ?UserProfile
    {
        return $this->userProfile;
    }

    public function setUserProfile(UserProfile $userProfile): self
    {
        // set the owning side of the relation if necessary
        if ($userProfile->getUser() !== $this) {
            $userProfile->setUser($this);
        }

        $this->userProfile = $userProfile;

        return $this;
    }

    /**
     * @return Collection<int, MiniTwits>
     */
    public function getLiked(): Collection
    {
        return $this->liked;
    }

    public function addLiked(MiniTwits $liked): self
    {
        if (!$this->liked->contains($liked)) {
            $this->liked->add($liked);
        }

        return $this;
    }

    public function removeLiked(MiniTwits $liked): self
    {
        $this->liked->removeElement($liked);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollows(): Collection
    {
        return $this->follows;
    }

    public function addFollow(self $follow): self
    {
        if (!$this->follows->contains($follow)) {
            $this->follows->add($follow);
        }

        return $this;
    }

    public function removeFollow(self $follow): self
    {
        $this->follows->removeElement($follow);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollowed(): Collection
    {
        return $this->followed;
    }

    public function addFollowed(self $followed): self
    {
        if (!$this->followed->contains($followed)) {
            $this->followed->add($followed);
            $followed->addFollow($this);
        }

        return $this;
    }

    public function removeFollowed(self $followed): self
    {
        if ($this->followed->removeElement($followed)) {
            $followed->removeFollow($this);
        }

        return $this;
    }

}
