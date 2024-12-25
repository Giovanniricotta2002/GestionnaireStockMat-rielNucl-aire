<?php

namespace App\Controller;

use App\Entity\MaterielInspection;
use App\Entity\Task;
use App\Repository\MaterielInspectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/task', name: 'app_task')]
class TaskController extends AbstractController
{
    private Serializer $serializer;

    public function __construct(
        private EntityManagerInterface $em
    ) {
        $normaliser = [new ObjectNormalizer()];
        $encoder = [new JsonEncoder(), new CsvEncoder(), new YamlEncoder(), new XmlEncoder()];

        $this->serializer = new Serializer($normaliser, $encoder);
    }
    
    // #[Route('/{taskId}', name: '_index')]
    // public function index(int $taskId): Response
    // {
    //     return $this->render('task/index.html.twig', [
    //         'controller_name' => 'TaskController',
    //     ]);
    // }

    #[Route('/new', name: '_new', methods: ['POST'])]
    public function newTask(Request $request, MaterielInspectionRepository $miRepository): Response
    {
        $content = $this->serializer->decode($request->getContent(), 'json');
        $materielInspectionArrays = $miRepository->findBy(['id' => $content]);
        
        $task = new Task();
        array_map(fn (MaterielInspection $mi) => $task->addMaterielInspect($mi), $materielInspectionArrays);
        $task->setName('Aucun nom');

        $this->em->persist($task);
        array_map(fn (MaterielInspection $mi) => $this->em->persist($mi), $materielInspectionArrays);

        $this->em->flush();

        dd($task->getId());

        return $this->json([$content]);
    }
}
