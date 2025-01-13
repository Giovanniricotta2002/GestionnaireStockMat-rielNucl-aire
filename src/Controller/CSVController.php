<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Utilisateur;
use App\Form\ImportFileType;
use App\Repository\TaskRepository;
use App\Service\FileUploader;
use App\Tools\ImportFile;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use chillerlan\QRCode\QRCode;
use Doctrine\ORM\EntityManagerInterface;
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
            $csvFileName = null;
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
    public function generatedQrCode(
        Request $request,
        EntityManagerInterface $em,
        TaskRepository $tRepository,
        #[CurrentUser] ?Utilisateur $user
    ): Response {
        if ($request->query->has('taskId')) {
            $task = $tRepository->find($request->query->get('taskId'));
            $task->setUtilisateurAffect($user);
            $em->persist($task);
            $em->flush();

            return $this->redirect($this->generateUrl('app_task_index', ['id' => $request->query->get('ids')]));
        }

        $data = $this->generateUrl(
            'app_task_index',
            ['id' => $request->query->get('ids')],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $this->render('csv/qrcode.html.twig', [
            'qrcode' => (new QRCode())->render($data),
        ]);
    }
}
