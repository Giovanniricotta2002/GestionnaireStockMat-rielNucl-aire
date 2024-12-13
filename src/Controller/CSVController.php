<?php

namespace App\Controller;

use App\Form\ImportFileType;
use App\Service\FileUploader;
use App\Tools\ImportFile;
use chillerlan\QRCode\QRCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    #[Route('/generated/qrcode', name: '_generated_qrcode')]
    public function generatedQrCode(Request $request): Response
    {
        $data = $this->generateUrl('app_csv_import', ['id' => 455], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->render('csv/qrcode.html.twig', [
            'controller_name' => 'CSVControllerQrCode',
            'd' => (new QRCode)->render($data),
        ]);
    }
}
