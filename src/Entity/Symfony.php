<?php

namespace App\Entity;

use App\Repository\SymfonyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SymfonyRepository::class)]
class Symfony
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = NULL;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = NULL;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = NULL;

    #[ORM\ManyToOne(inversedBy: 'symfonies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?Composer $composer = NULL;

    #[ORM\Column]
    #[Context([ DateTimeNormalizer::FORMAT_KEY => 'Y-m-d' ])]
    private ?\DateTimeImmutable $createdAt = NULL;

    #[ORM\Column(nullable: true)]
    #[Context([ DateTimeNormalizer::FORMAT_KEY => 'Y-m-d' ])]
    private ?\DateTimeImmutable $finishedAt = NULL;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getComposer(): ?Composer
    {
        return $this->composer;
    }

    public function setComposer(?Composer $composer): static
    {
        $this->composer = $composer;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeImmutable $finishedAt): static
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

}
