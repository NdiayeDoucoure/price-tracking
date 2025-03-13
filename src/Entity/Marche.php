<?php

namespace App\Entity;
use App\Enum\MarcheTypeEnum;

use App\Repository\MarcheRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: MarcheRepository::class)]
class Marche
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $localisation = null;

    #[ORM\Column(length: 10)]
    private ?MarcheTypeEnum $type = null;

    /**
     * @var Collection<int, Produit>
     */
    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'marche')]
    private Collection $produits;

    /**
     * @var Collection<int, HistoriquePrix>
     */
    #[ORM\OneToMany(targetEntity: HistoriquePrix::class, mappedBy: 'marche')]
    private Collection $historiques;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
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

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getType(): ?MarcheTypeEnum
    {
        return $this->type;
    }

    public function setType(MarcheTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setMarche($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getMarche() === $this) {
                $produit->setMarche(null);
            }
        }

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
            $historique->setMarche($this);
        }

        return $this;
    }

    public function removeHistorique(HistoriquePrix $historique): static
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getMarche() === $this) {
                $historique->setMarche(null);
            }
        }

        return $this;
    }
}
