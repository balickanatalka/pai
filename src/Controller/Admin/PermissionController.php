<?php

namespace App\Controller\Admin;

use App\Entity\Permission;
use App\Form\Admin\PermissionType;
use App\Repository\PermissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/permission', name: 'app_permission_')]
class PermissionController extends AbstractController
{
    public function __construct(
        private readonly PermissionRepository $permissionRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/list', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $permissions = $this->permissionRepository->findAll();

        return $this->render('admin/permission/index.html.twig', [
            'permissions' => $permissions,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $permission = new Permission();

        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($permission);
            $this->entityManager->flush();

            $this->addFlash('success', 'Permission has been created.');

            return $this->redirectToRoute('app_permission_index');
        }

        return $this->render('admin/permission/new.html.twig', [
            'permission' => $permission,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'show', methods: ['GET'])]
    public function show(Permission $permission): Response
    {
        return $this->render('admin/permission/show.html.twig', [
            'permission' => $permission,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Permission $permission): Response
    {
        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Permission has been updated.');

            return $this->redirectToRoute('app_permission_index');
        }

        return $this->render('admin/permission/edit.html.twig', [
            'permission' => $permission,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Permission $permission): Response
    {
        if ($this->isCsrfTokenValid('delete_permission_' . $permission->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($permission);
            $this->entityManager->flush();

            $this->addFlash('success', 'Permission has been deleted.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_permission_index');
    }
}
