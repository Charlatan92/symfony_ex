<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    /**
     * @Assert\NotBlank(message="Le titre ne peut pas être vide.")
     */
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $auteur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    /**
     * @Assert\Date(message="La date doit être valide.")
     * @Assert\GreaterThanOrEqual("2000-01-01", message="La date doit être après l'an 2000.")
     */
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    /**
     * @var File|null
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/jpeg", "image/png", "image/gif"},
     *     mimeTypesMessage = "Veuillez uploader une image valide (jpeg, png, gif)."
     * )
     */
    private $fichier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(?string $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    // Méthodes pour le champ url
    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }
    public function getFichier(): ?File
    {
        return $this->fichier;
    }

    public function setFichier(?File $fichier = null): self
    {
        $this->fichier = $fichier;

        return $this;
    }
}
