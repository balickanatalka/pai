<?php

declare(strict_types=1);

namespace App\Migrations;

use App\Entity\Permission;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class Version20260419105530_RbacInitialData extends AbstractMigration
{
    public function __construct(
        Connection $connection,
        LoggerInterface $logger,
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct($connection, $logger);
    }

    public function getDescription(): string
    {
        return 'Seed initial RBAC roles, permissions and users using Doctrine entities';
    }

    public function up(Schema $schema): void
    {
        // Structure already exists.
        // Data seeding is performed in postUp().
    }

    public function postUp(Schema $schema): void
    {
        // Avoid duplicate imports if the migration is re-executed manually.
        $existingAdmin = $this->em
            ->getRepository(User::class)
            ->findOneBy(['email' => 'admin@example.com']);

        if ($existingAdmin !== null) {
            return;
        }

        // ======================
        // Permissions
        // ======================
        $permissions = [];

        $permissionDefinitions = [
            'product.view' => 'Allows viewing products',
            'product.edit' => 'Allows editing products',
            'product.manage' => 'Allows full product management',
            'order.view.own' => 'Allows viewing own orders',
            'order.view.all' => 'Allows viewing all orders',
            'order.create' => 'Allows creating orders',
            'order.edit' => 'Allows editing orders',
            'invoice.view.own' => 'Allows viewing own invoices',
            'invoice.view.all' => 'Allows viewing all invoices',
            'invoice.create' => 'Allows creating invoices',
            'payment.manage' => 'Allows managing payments',
            'user.manage_roles' => 'Allows assigning roles to users',
            'user.manage_permissions' => 'Allows assigning direct permission overrides',
        ];

        foreach ($permissionDefinitions as $code => $description) {
            $permission = new Permission();
            $permission->setCode($code);
            $permission->setName($code);
            $permission->setDescription($description);

            $this->em->persist($permission);
            $permissions[$code] = $permission;
        }

        // ======================
        // Roles
        // ======================
        $adminRole = (new Role())
            ->setCode('ROLE_ADMIN')
            ->setName('Administrator')
            ->setDescription('Full system access')
            ->setIsSystem(true);

        $managerRole = (new Role())
            ->setCode('ROLE_MANAGER')
            ->setName('Manager')
            ->setDescription('Management access')
            ->setIsSystem(true);

        $employeeRole = (new Role())
            ->setCode('ROLE_EMPLOYEE')
            ->setName('Employee')
            ->setDescription('Employee access')
            ->setIsSystem(true);

        $customerRole = (new Role())
            ->setCode('ROLE_CUSTOMER')
            ->setName('Customer')
            ->setDescription('Customer access')
            ->setIsSystem(true);

        // ADMIN -> all permissions
        foreach ($permissions as $permission) {
            $adminRole->addPermission($permission);
        }

        // MANAGER
        foreach ([
                     'product.view',
                     'product.edit',
                     'product.manage',
                     'order.view.all',
                     'order.create',
                     'order.edit',
                     'invoice.view.all',
                     'invoice.create',
                     'payment.manage',
                 ] as $code) {
            $managerRole->addPermission($permissions[$code]);
        }

        // EMPLOYEE
        foreach ([
                     'product.view',
                     'order.view.all',
                     'order.create',
                     'invoice.view.all',
                 ] as $code) {
            $employeeRole->addPermission($permissions[$code]);
        }

        // CUSTOMER
        foreach ([
                     'product.view',
                     'order.view.own',
                     'order.create',
                     'invoice.view.own',
                 ] as $code) {
            $customerRole->addPermission($permissions[$code]);
        }

        foreach ([$adminRole, $managerRole, $employeeRole, $customerRole] as $role) {
            $this->em->persist($role);
        }

        $this->em->flush();

        // ======================
        // Users
        // ======================
        $users = [
            [
                'email' => 'admin@example.com',
                'password' => 'Admin123!',
                'role' => $adminRole,
            ],
            [
                'email' => 'manager@example.com',
                'password' => 'Manager123!',
                'role' => $managerRole,
            ],
            [
                'email' => 'employee@example.com',
                'password' => 'Employee123!',
                'role' => $employeeRole,
            ],
            [
                'email' => 'customer@example.com',
                'password' => 'Customer123!',
                'role' => $customerRole,
            ],
        ];

        foreach ($users as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setIsActive(true);

            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
            $user->addUserRole($data['role']);

            $this->em->persist($user);
        }

        $this->em->flush();
    }

    public function down(Schema $schema): void
    {
        // Keep rollback SQL-based to avoid ORM dependency during down migrations.
        $this->addSql("DELETE FROM user_permission");
        $this->addSql("DELETE FROM user_role");
        $this->addSql("DELETE FROM role_permission");
        $this->addSql("DELETE FROM app_user WHERE email IN ('admin@example.com', 'manager@example.com', 'employee@example.com', 'customer@example.com')");
        $this->addSql("DELETE FROM role WHERE code IN ('ROLE_ADMIN', 'ROLE_MANAGER', 'ROLE_EMPLOYEE', 'ROLE_CUSTOMER')");
        $this->addSql("DELETE FROM permission WHERE code IN (
            'product.view',
            'product.edit',
            'product.manage',
            'order.view.own',
            'order.view.all',
            'order.create',
            'order.edit',
            'invoice.view.own',
            'invoice.view.all',
            'invoice.create',
            'payment.manage',
            'user.manage_roles',
            'user.manage_permissions'
        )");
    }
}
