<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use App\Entity\Employee;
use App\Entity\LogEntry;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/log', name: 'app_log_')]
class LogEntryController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $entity = $request->query->get('entity');

        /** @var LogEntryRepository $repository */
        $repository = $this->entityManager->getRepository(LogEntry::class);

        $queryBuilder = $repository->createQueryBuilder('log')
            ->orderBy('log.loggedAt', 'DESC')
            ->addOrderBy('log.id', 'DESC');

        if ($entity !== null && $entity !== '') {
            $queryBuilder
                ->andWhere('log.objectClass = :entity')
                ->setParameter('entity', $this->resolveEntityClass($entity));
        }

        $logs = $queryBuilder
            ->setMaxResults(200)
            ->getQuery()
            ->getResult();

        return $this->render('admin/logEntry/index.html.twig', [
            'logs' => $logs,
            'selectedEntity' => $entity,
            'entities' => $this->getEntityFilters(),
        ]);
    }

    private function resolveEntityClass(string $entity): string
    {
        return match ($entity) {
            'user' => User::class,
            'customer' => Customer::class,
            'employee' => Employee::class,
            default => $entity,
        };
    }

    private function getEntityFilters(): array
    {
        return [
            'user' => 'User',
            'customer' => 'Customer',
            'employee' => 'Employee',
        ];
    }
}
