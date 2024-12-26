<?php

namespace App\Controller;

use App\Form\MaterielInspectionRechercheType;
use App\Repository\MaterielInspectionRepository;
use App\Tools\MaterielInspectionRecherche;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/recherche', name: 'app_recherche')]
class TableauRechercheController extends AbstractController
{
    private Serializer $serializer;

    public function __construct()
    {
        $normaliser = [new DateTimeNormalizer(['datetime_format' => 'Y-m-d H:i:s']), new ObjectNormalizer()];
        $encoder = [new JsonEncoder(), new CsvEncoder(), new YamlEncoder(), new XmlEncoder()];

        $this->serializer = new Serializer($normaliser, $encoder);
    }

    #[Route('/', name: '_init')]
    public function index(MaterielInspectionRecherche $materielInspectionRecherche, EntityManagerInterface $em, MaterielInspectionRepository $miRepository, Request $request): Response
    {
        $materielInspectionRecherche->setMaterielInspectionRepository($miRepository);

        $from = $this->createForm(MaterielInspectionRechercheType::class, $materielInspectionRecherche);
        $from->handleRequest($request);
        $datasKey = ['id', 'description', 'productCol', 'dateIntsall', 'dateInspect', 'status'];

        if ($from->isSubmitted() && $from->isValid()) {

            $recherche = $from->getData();

            $criteria = [];
            if ($recherche->getProductCol() ?? false) {
                $criteria['productCol'] = $recherche->getProductCol() ?? '';
            }

            if ($recherche->getDateInstallDebut()) {
                $criteria['dateIntsallDebut'] = $recherche->getDateInstallDebut()->format('Y-m-d H:i:s');
            }
            if ($recherche->getDateInstallFin()) {
                $criteria['dateIntsallFin'] = $recherche->getDateInstallFin();
            }
            if ($recherche->getDateInspectDebut()) {
                $criteria['dateInspectDebut'] = $recherche->getDateInspectDebut();
            }
            if ($recherche->getDateInspectFin()) {
                $criteria['dateInspectFin'] = $recherche->getDateInspectFin();
            }

            if ($recherche->getStatus()) {
                $criteria['status'] = $recherche->getStatus()->value;
            }

            $datas = $miRepository->findParms($criteria);
            $datas = $this->serializer->normalize($datas, 'json');
    

            return $this->render('recherche/index.html.twig', [
                'datas' => $datas,
                'dataKeys' => $datasKey,
                'form' => $from,
                'action' => true
            ]);
        }

        $datas = $miRepository->findAll();
        // dd($this->serializer->serialize($datas, 'json'));
        // $datas = $this->serializer->normalize($datas, 'json');

        return $this->render('recherche/index.html.twig', [
            'datas' => $datas,
            'dataKeys' => $datasKey,
            'form' => $from,
            'action' => true,
        ]);
    }

}
