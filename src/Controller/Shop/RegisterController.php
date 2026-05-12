<?php

namespace App\Controller\Shop;

use App\Entity\User;
use App\Form\Shop\RegisterType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/shop/register', name: 'app_shop_register')]
class RegisterController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly RoleRepository $roleRepository,
    ) {
    }

    #[Route('', name: '', methods: ['GET', 'POST'])]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_shop_dashboard');
        }

        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerRole = $this->roleRepository->findOneBy([
                'code' => 'ROLE_CUSTOMER',
            ]);

            if ($customerRole === null) {
                throw $this->createNotFoundException('ROLE_CUSTOMER was not found.');
            }

            $plainPassword = $form->get('plainPassword')->getData();

            $user
                ->setPassword($this->passwordHasher->hashPassword($user, $plainPassword))
                ->setIsActive(true)
                ->addUserRole($customerRole);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $email = (new Email())
                ->from('no-reply@example.com')
                ->to($user->getEmail())
                ->subject('Account registration')
                ->text('Your account has been created successfully.');



            $mailer->send($email);

            $this->addFlash('success', 'Your account has been created. Confirmation email has been sent.');

            return $this->redirectToRoute('app_shop_login');
        }

        return $this->render('shop/register/index.html.twig', [
            'form' => $form,
        ]);
    }
}
