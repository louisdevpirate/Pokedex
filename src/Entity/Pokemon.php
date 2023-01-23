<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: PokemonRepository::class)]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type2 = null;

    #[ORM\Column(length: 5000)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $ObtentionDate = null;

    #[ORM\Column(length: 50)]
    private ?string $gif = null;


    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType2(): ?string
    {
        return $this->type2;
    }

    public function setType2(?string $type2): self
    {
        $this->type2 = $type2;

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

    public function getObtentionDate(): ?\DateTimeInterface
    {
        return $this->ObtentionDate;
    }

    public function setObtentionDate(\DateTimeInterface $ObtentionDate): self
    {
        $this->ObtentionDate = $ObtentionDate;

        return $this;
    }

    public function getGif(): ?string
    {
        return $this->gif;
    }

    public function setGif(string $gif): self
    {
        $this->gif = $gif;

        return $this;
    }


}
