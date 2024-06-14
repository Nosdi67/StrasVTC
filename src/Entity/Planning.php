<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDispo = null;

    /**
     * @var Collection<int, Chauffeur>
     */
    #[ORM\OneToMany(targetEntity: Chauffeur::class, mappedBy: 'planning')]
    private Collection $chauffeur;

    public function __construct()
    {
        $this->chauffeur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDispo(): ?\DateTimeInterface
    {
        return $this->dateDispo;
    }

    public function setDateDispo(\DateTimeInterface $dateDispo): static
    {
        $this->dateDispo = $dateDispo;

        return $this;
    }

    /**
     * @return Collection<int, Chauffeur>
     */
    public function getChauffeur(): Collection
    {
        return $this->chauffeur;
    }

    public function addChauffeur(Chauffeur $chauffeur): static
    {
        if (!$this->chauffeur->contains($chauffeur)) {
            $this->chauffeur->add($chauffeur);
            $chauffeur->setPlanning($this);
        }

        return $this;
    }

    public function removeChauffeur(Chauffeur $chauffeur): static
    {
        if ($this->chauffeur->removeElement($chauffeur)) {
            // set the owning side to null (unless already changed)
            if ($chauffeur->getPlanning() === $this) {
                $chauffeur->setPlanning(null);
            }
        }

        return $this;
    }
}
