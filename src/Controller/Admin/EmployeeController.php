<?php

namespace App\Controller\Admin;

use App\Entity\Employee;
use App\Entity\User;
use App\Form\Admin\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/employee', name: 'app_employee_')]
class EmployeeController extends AbstractController
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/list', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $employees = $this->employeeRepository->findAll();

        return $this->render('admin/employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $employee = new Employee();

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($employee->getUser() instanceof User) {
                $employee->getUser()->setEmployee($employee);
            }

            $this->entityManager->persist($employee);
            $this->entityManager->flush();

            $this->addFlash('success', 'Employee has been created.');

            return $this->redirectToRoute('app_employee_index');
        }

        return $this->render('admin/employee/new.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        return $this->render('admin/employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employee $employee): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($employee->getUser() instanceof User) {
                $employee->getUser()->setEmployee($employee);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Employee has been updated.');

            return $this->redirectToRoute('app_employee_index');
        }

        return $this->render('admin/employee/edit.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Employee $employee): Response
    {
        if ($this->isCsrfTokenValid('delete_employee_' . $employee->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($employee);
            $this->entityManager->flush();

            $this->addFlash('success', 'Employee has been deleted.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_employee_index');
    }
}
