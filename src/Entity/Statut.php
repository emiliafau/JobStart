<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Table(name: "statut")]
#[ORM\Entity]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $statut = null;

    #[ORM\Column(type: "datetime_immutable")]
    private ?DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable(); // ou utiliser une valeur par défaut
    }

    // Getter et Setter pour $id
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter et Setter pour $statut
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    // Getter et Setter pour $createdAt
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }
}