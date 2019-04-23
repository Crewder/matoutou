<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartieRepository")
 */
class Partie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

   

    /**
     * @ORM\Column(type="text")
     */
    private $terrain_j1 = [];

    /**
     * @ORM\Column(type="text")
     */
    private $terrain_j2 = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $tour;

    /**
     * @ORM\Column(type="integer")
     */
    private $des = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $debut_partie;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fin_partie;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $duree_partie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $etat_partie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type_victoire;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Jouer", mappedBy="partie")
     */
    private $jouers;


    /**
     * @ORM\Column(type="integer")
     */
    private $move;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="j1_parties")
     */
    private $j1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="j2_parties")
     */
    private $j2;



    public function __construct()
    {
        $this->jouers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTerrainJ1(): array
    {
        $terrain_j1 = json_decode($this->terrain_j1, true);

        return $terrain_j1;
    }

    public function setTerrainJ1(array $terrain_j1): self
    {
        $this->terrain_j1 = json_encode($terrain_j1);

        return $this;
    }

    public function getTerrainJ2(): array
    {
        $terrain_j2 = json_decode($this->terrain_j2, true);

        return $terrain_j2;
    }

    public function setTerrainJ2(array $terrain_j2): self
    {
        $this->terrain_j2 = json_encode($terrain_j2);

        return $this;
    }


    public function getTour(): ?int
    {
        return $this->tour;
    }

    public function setTour(int $tour): self
    {
        $this->tour = $tour;

        return $this;
    }

    public function getDes(): array
    {
        $des = json_decode($this->des, true);

        return $des;
    }

    public function setDes(array $des): self
    {
        $this->des = json_encode($des);

        return $this;
    }

    

    public function getDebutPartie(): ?\DateTimeInterface
    {
        return $this->debut_partie;
    }

    public function setDebutPartie(\DateTimeInterface $debut_partie): self
    {
        $this->debut_partie = $debut_partie;

        return $this;
    }

    public function getFinPartie(): ?\DateTimeInterface
    {
        return $this->fin_partie;
    }

    public function setFinPartie(\DateTimeInterface $fin_partie): self
    {
        $this->fin_partie = $fin_partie;

        return $this;
    }

    public function getDureePartie(): ?\DateTimeInterface
    {
        return $this->duree_partie;
    }

    public function setDureePartie(\DateTimeInterface $duree_partie): self
    {
        $this->duree_partie = $duree_partie;

        return $this;
    }

    public function getEtatPartie(): ?int
    {
        return $this->etat_partie;
    }

    public function setEtatPartie(int $etat_partie): self
    {
        $this->etat_partie = $etat_partie;

        return $this;
    }

    public function getTypeVictoire(): ?bool
    {
        return $this->type_victoire;
    }

    public function setTypeVictoire(bool $type_victoire): self
    {
        $this->type_victoire = $type_victoire;

        return $this;
    }


    /**
     * @return Collection|Jouer[]
     */
    public function getJouers(): Collection
    {
        return $this->jouers;
    }

    public function addJouer(Jouer $jouer): self
    {
        if (!$this->jouers->contains($jouer)) {
            $this->jouers[] = $jouer;
            $jouer->setPartie($this);
        }

        return $this;
    }

    public function removeJouer(Jouer $jouer): self
    {
        if ($this->jouers->contains($jouer)) {
            $this->jouers->removeElement($jouer);
            // set the owning side to null (unless already changed)
            if ($jouer->getPartie() === $this) {
                $jouer->setPartie(null);
            }
        }

        return $this;
    }

    

    public function getMove(): ?int
    {
        return $this->move;
    }

    public function setMove(int $move): self
    {
        $this->move = $move;

        return $this;
    }

    public function getJ1(): ?user
    {
        return $this->j1;
    }

    public function setJ1(?user $j1): self
    {
        $this->j1 = $j1;

        return $this;
    }

    public function getJ2(): ?user
    {
        return $this->j2;
    }

    public function setJ2(?user $j2): self
    {
        $this->j2 = $j2;

        return $this;
    }



}
