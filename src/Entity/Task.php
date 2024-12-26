<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, MaterielInspection>
     */
    #[ORM\OneToMany(targetEntity: MaterielInspection::class, mappedBy: 'task')]
    private Collection $materielInspect;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Utilisateur $utilisateurAffect = null;

    public function __construct()
    {
        $this->materielInspect = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, MaterielInspection>
     */
    public function getMaterielInspect(): Collection
    {
        return $this->materielInspect;
    }

    public function addMaterielInspect(MaterielInspection $materielInspect): static
    {
        if (!$this->materielInspect->contains($materielInspect)) {
            $this->materielInspect->add($materielInspect);
            $materielInspect->setTask($this);
        }

        return $this;
    }

    public function removeMaterielInspect(MaterielInspection $materielInspect): static
    {
        if ($this->materielInspect->removeElement($materielInspect)) {
            // set the owning side to null (unless already changed)
            if ($materielInspect->getTask() === $this) {
                $materielInspect->setTask(null);
            }
        }

        return $this;
    }

    public function getUtilisateurAffect(): ?Utilisateur
    {
        return $this->utilisateurAffect;
    }

    public function setUtilisateurAffect(?Utilisateur $utilisateurAffect): static
    {
        $this->utilisateurAffect = $utilisateurAffect;

        return $this;
    }
}
