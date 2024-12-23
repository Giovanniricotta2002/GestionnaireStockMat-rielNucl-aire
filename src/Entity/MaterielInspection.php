<?php

namespace App\Entity;

use App\Enum\Status;
use App\Repository\MaterielInspectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielInspectionRepository::class)]
class MaterielInspection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    private ?string $productCol = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateIntsall = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateInspect = null;
    
    #[ORM\Column(type: 'string', enumType: Status::class)]
    private ?Status $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'materielInspect')]
    private ?Task $task = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getProductCol(): ?string
    {
        return $this->productCol;
    }

    public function setProductCol(string $productCol): static
    {
        $this->productCol = $productCol;

        return $this;
    }

    public function getDateIntsall(): ?\DateTimeInterface
    {
        return $this->dateIntsall;
    }

    public function setDateIntsall(\DateTimeInterface $dateIntsall): static
    {
        $this->dateIntsall = $dateIntsall;

        return $this;
    }

    public function getDateInspect(): ?\DateTimeInterface
    {
        return $this->dateInspect;
    }

    public function setDateInspect(\DateTimeInterface $dateInspect): static
    {
        $this->dateInspect = $dateInspect;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;

        return $this;
    }
}
