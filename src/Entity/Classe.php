<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClasseRepository::class)
 */
class Classe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Eleve::class, mappedBy="classe")
     */
    private $eleves;

    /**
     * moyenne d'une classe
     */
    private $moyenneClasse;


    public function __construct()
    {
        $this->eleves = new ArrayCollection();
    }

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

    public function getMoyenneClasse(): ?float{
        return $this->moyenneClasse;
    }

    public function seMoyenneClasse(float $moyenne): self
    {
        $this->moyenneClasse = $moyenne;

        return $this;
    }

    /**
     * @return Collection|Eleve[]
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addElefe(Eleve $eleve): self
    {
        if (!$this->eleves->contains($eleve)) {
            $this->eleves[] = $eleve;
            $eleve->setClasse($this);
        }

        return $this;
    }

    public function removeElefe(Eleve $eleve): self
    {
        if ($this->eleves->removeElement($eleve)) {
            // set the owning side to null (unless already changed)
            if ($eleve->getClasse() === $this) {
                $eleve->setClasse(null);
            }
        }

        return $this;
    }
}
