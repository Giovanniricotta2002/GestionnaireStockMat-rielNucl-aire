<?php

namespace App\Controller;

use App\Form\ImportFileType;
use App\Service\FileUploader;
use App\Tools\ImportFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/csv', name: 'app_csv')]
class CSVController extends AbstractController
{
    #[Route('/import', name: '_import')]
    public function import(Request $request, FileUploader $fileUploader): Response
    {
        $importFile = new ImportFile();

        $form = $this->createForm(ImportFileType::class, $importFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('fichier')->getData();
            if ($brochureFile) {
                $csvFileName = $fileUploader->upload($brochureFile);
            }
            $csvFileName->serializeFile();
            $csvFileName->importData();
        }

        return $this->render('csv/index.html.twig', [
            'controller_name' => 'CSVController',
            'form' => $form,
        ]);
    }

    #[Route('/import/qrcode', name: '_import_qrcode')]
    public function importQrCode(Request $request): Response
    {
        return $this->render('csv/qrcode.html.twig', [
            'controller_name' => 'CSVControllerQrCode',
        ]);
    }
}
