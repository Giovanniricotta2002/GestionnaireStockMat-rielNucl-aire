<?php

namespace App\Tools;

use Symfony\Component\HttpFoundation\File\File;

class ImportFile
{
    private string $name = '';
    private string $fichier2 = '';
    private ?File $fichier = null;


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $_name): ImportFile {
        $this->name = $_name;

        return $this;
    }

    public function getFichier(): ?File
    {
        return $this->fichier;
    }

    public function setFichier(File $_fichier): ImportFile
    {
        $this->fichier = $_fichier;

        return $this;
    }
}
