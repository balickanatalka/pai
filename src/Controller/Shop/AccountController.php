<?php

namespace App\Controller\Shop;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\Shop\CustomerProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/shop/account', name: 'app_shop_account_')]
class AccountController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute('app_shop_login');
        }

        $customer = $user->getCustomer();

        if (!$customer instanceof Customer) {
            $customer = new Customer();
            $customer->setUser($user);
            $customer->setEmail($user->getEmail());
        }

        $form = $this->createForm(CustomerProfileType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            $this->addFlash('success', 'Customer details have been saved.');

            return $this->redirectToRoute('app_shop_account_index');
        }

        return $this->render('shop/account/index.html.twig', [
            'user' => $user,
            'customer' => $customer,
            'form' => $form,
        ]);
    }
}
