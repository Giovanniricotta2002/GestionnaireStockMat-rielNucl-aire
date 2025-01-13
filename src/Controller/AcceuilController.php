<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\MakeUserType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/', name: 'app_acceuil')]
class AcceuilController extends AbstractController
{
    private Serializer $serializer;

    public function __construct()
    {
        $normaliser = [new ObjectNormalizer()];
        $encoder = [new JsonEncoder(), new CsvEncoder(), new YamlEncoder(), new XmlEncoder()];

        $this->serializer = new Serializer($normaliser, $encoder);
    }

    #[Route('', name: '_index')]
    public function index(#[CurrentUser()] ?Utilisateur $user, EntityManagerInterface $em): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_acceuil_index_admin');
        }

        return $this->render('acceuil/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: '_index_admin')]
    public function indexAdmin(
        #[CurrentUser()] ?Utilisateur $admin,
        EntityManagerInterface $em,
        UtilisateurRepository $uRepository,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
    ): Response {
        $newUser = new Utilisateur();
        $from = $this->createForm(MakeUserType::class, $newUser);

        $from->handleRequest($request);

        if ($from->isSubmitted() && $from->isValid()) {
            $newUser = $this->serializer->normalize($from->getData(), 'json');

            $u = new Utilisateur();
            $hashedPassword = $passwordHasher->hashPassword($u, $newUser['password']);

            $u
                ->setUsername($newUser['username'])
                ->setPassword($hashedPassword)
                ->setRoles($newUser['roles'])
            ;

            $em->persist($u);
            $em->flush();

            $this->addFlash('message', 'Nouvelle Utilisateur');
        }

        return $this->render('acceuil/admin.html.twig', [
            'user' => $admin,
            'dataKeys' => ['id', 'UserName', 'Roles'],
            'action' => false,
            'datas' => $this->serializer->normalize(
                array_map(
                    fn (Utilisateur $user) => [
                        'id' => $user->getId(),
                        'username' => $user->getUsername(),
                        'roles' => $user->getRoles(),
                    ],
                    $uRepository->findAll()
                )
            ),
            'form' => $from->createView(),
        ]);
    }
}
