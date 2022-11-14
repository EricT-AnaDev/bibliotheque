<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Livres::class, mappedBy="category")
     */
    private $livre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    public function __construct()
    {
        $this->livre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Livres>
     */
    public function getLivre(): Collection
    {
        return $this->livre;
    }

    public function addLivre(Livres $livre): self
    {
        if (!$this->livre->contains($livre)) {
            $this->livre[] = $livre;
            $livre->setCategory($this);
        }

        return $this;
    }

    public function removeLivre(Livres $livre): self
    {
        if ($this->livre->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getCategory() === $this) {
                $livre->setCategory(null);
            }
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function __toString()
    {
        if(is_null($this->nom))
        {
            return "NULL";
        }
        return $this->nom;
    }
}
