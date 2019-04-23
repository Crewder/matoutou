<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartesRepository")
 */
class Cartes
{


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $force_carte;

    /**
     * @ORM\Column(type="integer")
     */
    private $type_carte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $couleur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $team;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_carte;

    public function isShogun(){
        return $this->getForceCarte() == 4;
    }

    public function isBleu(){
        return $this->getTypeCarte() == 1;
    }

    public function isBlanc(){
        return $this->getTypeCarte() == 2;
    }

    public function isRouge(){
        return $this->getTypeCarte() == 3;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getForceCarte(): ?int
    {
        return $this->force_carte;
    }

    public function setForceCarte(int $force_carte): self
    {
        $this->force_carte = $force_carte;

        return $this;
    }

    public function getTeam(): ?string
    {
        return $this->team;
    }

    public function setTeam(string $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getTypeCarte(): ?string
    {
        return $this->type_carte;
    }

    public function setTypeCarte(string $type_carte): self
    {
        $this->type_carte = $type_carte;

        return $this;
    }

    public function getImageCarte(): ?string
    {
        return $this->image_carte;
    }

    public function setImageCarte(string $image_carte): self
    {
        $this->image_carte = $image_carte;

        return $this;
    }
}
