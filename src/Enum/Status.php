<?php

namespace App\Enum;

enum Status: string
{
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
}