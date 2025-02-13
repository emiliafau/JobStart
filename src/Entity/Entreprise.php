<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "entreprise")]
#[ORM\Entity]
class Entreprise
{   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null; 

    #[ORM\OneToOne(targetEntity: Utilisateur::class, inversedBy: "entreprise")]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(type: "string", length: 14, unique: true)]
    private ?string $siret = null;

    // Getter et Setter pour l'utilisateur lié
    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    // Getter et Setter pour le numéro de SIRET
    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}
