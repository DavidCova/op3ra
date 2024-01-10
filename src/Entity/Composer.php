<?php

namespace App\Entity;

use App\Repository\ComposerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ComposerRepository::class)]
class Composer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    #[Groups(['create', 'update'])]
    private ?string $firstName = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    #[Groups(['create', 'update'])]
    private ?string $lastName = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    #[Groups(['create', 'update'])]
    private ?\DateTimeImmutable $dateOfBirth = null;

    #[Assert\NotBlank]
    #[Assert\Country]
    #[ORM\Column(length: 2)]
    #[Groups(['create', 'update'])]
    private ?string $countryCode = null;

    #[ORM\OneToMany(mappedBy: 'composer', targetEntity: Symphony::class, orphanRemoval: true)]
    #[Ignore]
    private Collection $symphonies;

    public function __construct()
    {
        $this->symphonies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeImmutable $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return Collection<int, Symphony>
     */
    public function getSymphonies(): Collection
    {
        return $this->symphonies;
    }

    public function addSymphony(Symphony $symphony): self
    {
        if (!$this->symphonies->contains($symphony)) {
            $this->symphonies->add($symphony);
            $symphony->setComposer($this);
        }

        return $this;
    }

    public function removeSymphony(Symphony $symphony): self
    {
        if ($this->symphonies->removeElement($symphony)) {
            // set the owning side to null (unless already changed)
            if ($symphony->getComposer() === $this) {
                $symphony->setComposer(null);
            }
        }

        return $this;
    }
}
