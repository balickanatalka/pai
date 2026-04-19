<?php

namespace App\Form\Admin;

use App\Entity\Permission;
use App\Entity\Role;
use App\Repository\PermissionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
    public function __construct(
        private readonly PermissionRepository $permissionRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code',
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('permissions', EntityType::class, [
                'class' => Permission::class,
                'choice_label' => 'code',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (PermissionRepository $repository) {
                    return $repository
                        ->createQueryBuilder('p')
                        ->orderBy('p.code', 'ASC');
                },
            ])
            ->add('isSystem', CheckboxType::class, [
                'label' => 'System role',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
