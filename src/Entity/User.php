<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */

 /**
 * @ORM\Entity
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Cette adresse email existe déjà!"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    

    public $recaptcha;

    /**
     * @ORM\Column(type="text")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Jouer", mappedBy="user")
     */
    private $jouers;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_inscription;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $derniere_co;


  /**
   * @ORM\Column(type="boolean")
   */
  private $statut_joueur;

  /**
   * @ORM\Column(type="boolean")
   */
  private $etat_joueur;

  /**
   * @ORM\Column(type="integer")
   */
  private $avertissement;


  /**
   * @ORM\Column(type="text", nullable=true)
   */
  private $friend_list = [];


  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $token;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Partie", mappedBy="j1")
   */
  private $j1_parties;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Partie", mappedBy="j2")
   */
  private $j2_parties;





    public function __construct()
    {
        $this->jouers = new ArrayCollection();
        $this->demandes = new ArrayCollection();
        $this->j1_parties = new ArrayCollection();
        $this->j2_parties = new ArrayCollection();


    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = json_decode($this->roles, true);
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = json_encode($roles);

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

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
            $jouer->setUser($this);
        }

        return $this;
    }

    public function removeJouer(Jouer $jouer): self
    {
        if ($this->jouers->contains($jouer)) {
            $this->jouers->removeElement($jouer);
            // set the owning side to null (unless already changed)
            if ($jouer->getUser() === $this) {
                $jouer->setUser(null);
            }
        }

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTimeInterface $date_inscription): self
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    public function getDerniereCo(): ?\DateTimeInterface
    {
        return $this->derniere_co;
    }

    public function setDerniereCo(\DateTimeInterface $derniere_co): self
    {
        $this->derniere_co = $derniere_co;

        return $this;
    }

    public function getStatutJoueur(): ?bool
    {
        return $this->statut_joueur;
    }

    public function setStatutJoueur(bool $statut_joueur): self
    {
        $this->statut_joueur = $statut_joueur;

        return $this;
    }

    public function getEtatJoueur(): ?bool
    {
        return $this->etat_joueur;
    }

    public function setEtatJoueur(bool $etat_joueur): self
    {
        $this->etat_joueur = $etat_joueur;

        return $this;
    }

    public function getAvertissement(): ?int
    {
        return $this->avertissement;
    }

    public function setAvertissement(int $avertissement): self
    {
        $this->avertissement = $avertissement;

        return $this;
    }

    public function getFriendList(): array
    {
        $friend_list = json_decode($this->friend_list, true);

        return $friend_list;
    }

    public function setFriendList(array $friend_list): self
    {
        $this->friend_list = json_encode($friend_list);

        return $this;
    }


    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }
    /**
     * Get the value of recaptcha
     */ 
    public function getRecaptcha()
    {
        return $this->recaptcha;
    }

    /**
     * Set the value of recaptcha
     *
     * @return  self
     */ 
    public function setRecaptcha($recaptcha)
    {
        $this->recaptcha = $recaptcha;

        return $this;
    }

    /**
     * @return Collection|Partie[]
     */
    public function getJ1Parties(): Collection
    {
        return $this->j1_parties;
    }

    public function addJ1Party(Partie $j1Party): self
    {
        if (!$this->j1_parties->contains($j1Party)) {
            $this->j1_parties[] = $j1Party;
            $j1Party->setJ1($this);
        }

        return $this;
    }

    public function removeJ1Party(Partie $j1Party): self
    {
        if ($this->j1_parties->contains($j1Party)) {
            $this->j1_parties->removeElement($j1Party);
            // set the owning side to null (unless already changed)
            if ($j1Party->getJ1() === $this) {
                $j1Party->setJ1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Partie[]
     */
    public function getJ2Parties(): Collection
    {
        return $this->j2_parties;
    }

    public function addJ2Party(Partie $j2Party): self
    {
        if (!$this->j2_parties->contains($j2Party)) {
            $this->j2_parties[] = $j2Party;
            $j2Party->setJ2($this);
        }

        return $this;
    }

    public function removeJ2Party(Partie $j2Party): self
    {
        if ($this->j2_parties->contains($j2Party)) {
            $this->j2_parties->removeElement($j2Party);
            // set the owning side to null (unless already changed)
            if ($j2Party->getJ2() === $this) {
                $j2Party->setJ2(null);
            }
        }

        return $this;
    }

   

   

}
