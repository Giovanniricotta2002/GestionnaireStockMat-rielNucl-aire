<?php

namespace App\Enum;

enum Status: string
{
    case Null = '';
    case EnAttente = 'en_attente';
    case Inspecte = 'inspecte';
    case Bon = 'bon';
    case Mauvais = 'mauvais';
    case Installe = 'installe';
    case Repare = 'repare';
    case Remplace = 'remplace';
    case HorsService = 'hors_service';
    case EnCoursInstallation = 'en_cours_installation';
    case Retire = 'retire';

    public function getLabel(): string
    {
        return match($this) {
            self::Null => '',
            self::EnAttente => 'En Attente',
            self::Inspecte => 'Inspecte',
            self::Bon => 'Bon',
            self::Mauvais => 'Mauvais',
            self::Installe => 'Installe',
            self::Repare => 'Repare',
            self::Remplace => 'Remplace',
            self::HorsService => 'HS',
            self::EnCoursInstallation => 'En Cours Installation',
            self::Retire => 'Retire',
        };
    }
}