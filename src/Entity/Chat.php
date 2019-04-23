<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 */
class Chat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $partie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $j1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $j2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getPartie(): ?int
    {
        return $this->partie;
    }

    public function setPartie(?int $partie): self
    {
        $this->partie = $partie;

        return $this;
    }

    public function getJ1(): ?int
    {
        return $this->j1;
    }

    public function setJ1(?int $j1): self
    {
        $this->j1 = $j1;

        return $this;
    }

    public function getJ2(): ?int
    {
        return $this->j2;
    }

    public function setJ2(?int $j2): self
    {
        $this->j2 = $j2;

        return $this;
    }
}
