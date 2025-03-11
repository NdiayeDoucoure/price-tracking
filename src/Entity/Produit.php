<?php

namespace App\Entity;

use App\Enum\CategorieEnum;
use App\Repository\ProduitRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix = null;

    #[ORM\Column(length: 10)]
    private ?CategorieEnum $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Marche $marche = null;

    /**
     * @var Collection<int, HistoriquePrix>
     */
    #[ORM\OneToMany(targetEntity: HistoriquePrix::class, mappedBy: 'produit')]
    private Collection $historiques;

    public function __construct()
    {
        $this->historiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCategorie(): ?CategorieEnum
    {
        return $this->categorie;
    }

    public function setCategorie(CategorieEnum $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getMarche(): ?Marche
    {
        return $this->marche;
    }

    public function setMarche(?Marche $marche): static
    {
        $this->marche = $marche;

        return $this;
    }

    /**
     * @return Collection<int, HistoriquePrix>
     */
    public function getHistoriques(): Collection
    {
        return $this->historiques;
    }

    public function addHistorique(HistoriquePrix $historique): static
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques->add($historique);
            $historique->setProduit($this);
        }

        return $this;
    }

    public function removeHistorique(HistoriquePrix $historique): static
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getProduit() === $this) {
                $historique->setProduit(null);
            }
        }

        return $this;
    }
}
