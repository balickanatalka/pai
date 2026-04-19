<?php

namespace App\Controller\Admin;

use App\Entity\Role;
use App\Form\Admin\RoleType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/role', name: 'app_role_')]
class RoleController extends AbstractController
{
    public function __construct(
        private readonly RoleRepository $roleRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/list', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $roles = $this->roleRepository->findAll();

        return $this->render('admin/role/index.html.twig', [
            'roles' => $roles,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $role = new Role();

        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($role);
            $this->entityManager->flush();

            $this->addFlash('success', 'Role has been created.');

            return $this->redirectToRoute('app_role_index');
        }

        return $this->render('admin/role/new.html.twig', [
            'role' => $role,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET'])]
    public function show(Role $role): Response
    {
        return $this->render('admin/role/show.html.twig', [
            'role' => $role,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Role $role): Response
    {
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Role has been updated.');

            return $this->redirectToRoute('app_role_index');
        }

        return $this->render('admin/role/edit.html.twig', [
            'role' => $role,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Role $role): Response
    {
        if ($role->isSystem()) {
            $this->addFlash('error', 'System role cannot be deleted.');

            return $this->redirectToRoute('app_role_index');
        }

        if ($this->isCsrfTokenValid('delete_role_' . $role->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($role);
            $this->entityManager->flush();

            $this->addFlash('success', 'Role has been deleted.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_role_index');
    }
}
