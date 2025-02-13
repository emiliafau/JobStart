<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(type: "string", length: 50)]
    private $prenom;

    #[ORM\Column(type: "string", length: 320, unique: true)]
    private $mail;

    #[ORM\Column(type: "string", length: 255)]
    private $motDePasse;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $token;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private $createdAt;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private string $role;

    #[ORM\OneToOne(mappedBy: "utilisateur", targetEntity: Candidat::class)]
    private ?Candidat $candidat = null;

    #[ORM\OneToOne(mappedBy: "utilisateur", targetEntity: Entreprise::class)]
    private ?Entreprise $entreprise = null;  

    public const ROLE_CANDIDAT = 'candidat';
    public const ROLE_ENTREPRISE = 'entreprise';

    // Getter et setter pour $id
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter et setter pour $nom
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    // Getter et setter pour $prenom
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    // Getter et setter pour $mail
    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;
        return $this;
    }

    // Getter et setter pour $motDePasse
    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;
        return $this;
    }

    // Getter et setter pour $token
    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }

    // Getter et setter pour $createdAt
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // Getter et setter pour $role
    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    // Getter et setter pour l'entreprise

    public function getEntreprise(): ?entreprise
{
    return $this->entreprise;
}

public function setEntreprise(?entreprise $entreprise): self
{
    $this->entreprise = $entreprise;

    // Met à jour l'entité entreprise pour refléter l'association bidirectionnelle
    if ($entreprise !== null && $entreprise->getUtilisateur() !== $this) {
        $entreprise->setUtilisateur($this);
    }

    return $this;
}

// Getter et setter pour l'entreprise

public function getCandidat(): ?candidat
{
    return $this->candidat;
}

public function setCandidat(?candidat $candidat): self
{
    $this->candidat = $candidat;
    return $this;
}

}

