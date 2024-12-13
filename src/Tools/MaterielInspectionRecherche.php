<?php

namespace App\Tools;

use App\Enum\Status;
use App\Repository\MaterielInspectionRepository;
use Doctrine\ORM\EntityManagerInterface;

class MaterielInspectionRecherche
{
    private string $productCol = '';
    private ?\DateTimeInterface $dateInstallDebut = null;
    private ?\DateTimeInterface $dateInstallFin = null;
    private ?\DateTimeInterface $dateInspectDebut = null;
    private ?\DateTimeInterface $dateInspectFin = null;
    private Status $status = Status::Null;
    private mixed $rechercher;

    private EntityManagerInterface $em;
    private MaterielInspectionRepository $miRepository;

    public function __construct()
    {
    }

    public function getProductCol(): ?string {
        return $this->productCol;
    }
    
    public function setProductCol(?string $ProductCol): MaterielInspectionRecherche {
        $this->productCol = $ProductCol;
    
        return $this;
    }

    public function getDateInstallDebut(): ?\DateTimeInterface {
        return $this->dateInstallDebut;
    }
    
    public function setDateInstallDebut(?\DateTimeInterface $dateinstalldebut): MaterielInspectionRecherche {
        $this->dateInstallDebut = $dateinstalldebut;
    
        return $this;
    }

    public function getDateInstallFin(): ?\DateTimeInterface {
        return $this->dateInstallFin;
    }
    
    public function setDateInstallFin(?\DateTimeInterface $dateinstallfin): MaterielInspectionRecherche {
        $this->dateInstallFin = $dateinstallfin;
    
        return $this;
    }

    public function getDateInspectDebut(): ?\DateTimeInterface {
        return $this->dateInspectDebut;
    }
    
    public function setDateInspectDebut(?\DateTimeInterface $dateinspectdebut): MaterielInspectionRecherche {
        $this->dateInspectDebut = $dateinspectdebut;
    
        return $this;
    }

    public function getDateInspectFin(): ?\DateTimeInterface {
        return $this->dateInspectFin;
    }
    
    public function setDateInspectFin(?\DateTimeInterface $dateinspectfin): MaterielInspectionRecherche {
        $this->dateInspectFin = $dateinspectfin;
    
        return $this;
    }

    public function getStatus(): ?Status {
        return $this->status;
    }
    
    public function setStatus(?Status $status): MaterielInspectionRecherche {
        $this->status = $status;
    
        return $this;
    }

    public function getEntityManager(): EntityManagerInterface {
        return $this->em;
    }
    
    public function setEntityManager(EntityManagerInterface $entitymanager): MaterielInspectionRecherche {
        $this->em = $entitymanager;
    
        return $this;
    }

    public function getMaterielInspectionRepository(): MaterielInspectionRepository {
        return $this->miRepository;
    }
    
    public function setMaterielInspectionRepository(MaterielInspectionRepository $materielinspectionrepository): MaterielInspectionRecherche {
        $this->miRepository = $materielinspectionrepository;
    
        return $this;
    }
}
