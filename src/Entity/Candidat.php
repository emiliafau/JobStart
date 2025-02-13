<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Candidat
{   
     #[ORM\Id]
     #[ORM\OneToOne(targetEntity: Utilisateur::class, inversedBy: "candidat")]
     #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id", onDelete: "CASCADE")]
    
    private ?Utilisateur $utilisateur = null;

    
     #[ORM\Column(type: "string", length: 255, unique: true)]
    
    private ?string $cv = null;

    // Getter et setter pour $utilisateur
    public function getUtilisateur():?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    // Getter et setter pour $cv
    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }
}