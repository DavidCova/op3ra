<?php

namespace App\Entity;

use App\Repository\ComposerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
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

    #[Assert\NotBlank(message:'Cannot be null!')]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    private ?\DateTimeImmutable $dateOfBirth = null;

    #[Assert\NotBlank]
    #[Assert\Country]
    #[ORM\Column(length: 2)]
    private ?string $countryCode = null;

    #[ORM\OneToMany(mappedBy: 'composer', targetEntity: Symfony::class, orphanRemoval: true)]
    #[Ignore]
    private Collection $symfonies;

    public function __construct()
    {
        $this->symfonies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeImmutable $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return Collection<int, Symfony>
     */
    public function getSymfonies(): Collection
    {
        return $this->symfonies;
    }

    public function addSymfony(Symfony $symfony): static
    {
        if (!$this->symfonies->contains($symfony)) {
            $this->symfonies->add($symfony);
            $symfony->setComposer($this);
        }

        return $this;
    }

    public function removeSymfony(Symfony $symfony): static
    {
        if ($this->symfonies->removeElement($symfony)) {
            // set the owning side to null (unless already changed)
            if ($symfony->getComposer() === $this) {
                $symfony->setComposer(null);
            }
        }

        return $this;
    }
}
