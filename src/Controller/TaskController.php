<?php

namespace App\Controller;

use App\Entity\MaterielInspection;
use App\Entity\Task;
use App\Enum\Status;
use App\Repository\MaterielInspectionRepository;
use App\Repository\TaskRepository;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use chillerlan\QRCode\QRCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
        private EntityManagerInterface $em,
    ) {
        $normaliser = [new DateTimeNormalizer(['datetime_format' => 'Y-m-d\TH:i:s']),  new ObjectNormalizer()];
        $encoder = [new JsonEncoder(), new CsvEncoder(), new YamlEncoder(), new XmlEncoder()];

        $this->serializer = new Serializer($normaliser, $encoder);
    }

    #[Route('/', name: '_index')]
    public function index(TaskRepository $tRepository): Response
    {
        $datas = $tRepository->findAll();
        $dataKeys = [
            'Id', 'Nom', 'Utilisateur Affecter', 'QrCode',
        ];

        $unset = [];
        foreach ($datas as $data) {
            $unset[] = [
                'id' => $data->getId(),
                'name' => $data->getName(),
                'userIdentifier' => $data->getUtilisateurAffect() == null
                    ? null
                    : $data->getUtilisateurAffect()->getUserIdentifier(),
                'qrcode' => null == $data->getUtilisateurAffect()
                    ? null
                    : (new QRCode())
                        ->render(
                            $this->generateUrl(
                                'app_task_index',
                                ['id' => $data->getId()],
                                UrlGeneratorInterface::ABSOLUTE_URL
                            )
                        ),
            ];
        }

        $datas = $unset;
        unset($unset);
        // $datas = $this->serializer->normalize($datas, 'json');

        return $this->render('task/index.html.twig', [
            'datas' => $datas,
            'dataKeys' => $dataKeys,
            'action' => true,
        ]);
    }

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

        return $this->json([$content]);
    }

    #[Route('/action/{taskId<\d*>}', name: '_action')]
    public function edit(Task $taskId, MaterielInspectionRepository $miRepository): Response
    {
        $data = array_map(fn (MaterielInspection $mi) => $this->serializer->normalize($mi, 'json', [
            AbstractNormalizer::ATTRIBUTES => [
                'id',
                'producCol',
                'dateIntsall',
                'dateInspect',
                'status',
                'description',
            ],
        ]), $taskId->getMaterielInspect()->getValues());

        // dd($this->serializer->normalize(Status::cases(), 'json'));

        return $this->render('task/action.html.twig', [
            'datas' => [$data],
            'dataKeys' => ['Description', 'Date Install', 'Date Inspect', 'Status'],
            'nom' => $taskId->getName(),
            'action' => false,
            'status' => $this->serializer->normalize(Status::cases(), 'json'),
        ]);
    }
}
