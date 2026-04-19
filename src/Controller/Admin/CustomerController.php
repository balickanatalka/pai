<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use App\Form\Admin\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/customer', name: 'app_customer_')]
class CustomerController extends AbstractController
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/list', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/customer/index.html.twig', [
            'customers' => $this->customerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $customer = new Customer();

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            $this->addFlash('success', 'Customer created');

            return $this->redirectToRoute('app_customer_index');
        }

        return $this->render('admin/customer/new.html.twig', [
            'form' => $form,
            'customer' => $customer,
        ]);
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET'])]
    public function show(Customer $customer): Response
    {
        return $this->render('admin/customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Customer $customer): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Customer updated');

            return $this->redirectToRoute('app_customer_index');
        }

        return $this->render('admin/customer/edit.html.twig', [
            'form' => $form,
            'customer' => $customer,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Customer $customer): Response
    {
        if ($this->isCsrfTokenValid('delete_customer_' . $customer->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($customer);
            $this->entityManager->flush();

            $this->addFlash('success', 'Customer deleted');
        }

        return $this->redirectToRoute('app_customer_index');
    }
}
