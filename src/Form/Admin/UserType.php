<?php

namespace App\Form\Admin;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function __construct(
        private readonly RoleRepository $roleRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'];

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('firstName', TextType::class, [
                'label' => 'First name',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last name',
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => $isEdit ? 'New password' : 'Password',
                'mapped' => false,
                'required' => !$isEdit,
                'empty_data' => '',
                'help' => $isEdit ? 'Leave empty to keep the current password.' : null,
            ])
            ->add('userRoles', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'code',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (RoleRepository $repository) {
                    return $repository
                        ->createQueryBuilder('r')
                        ->orderBy('r.code', 'ASC');
                },
                'label' => 'Roles',
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
            ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
            $user = $event->getData();
            $form = $event->getForm();

            if (!$user instanceof User) {
                return;
            }

            $submittedRoles = $form->get('userRoles')->getData();

            foreach ($user->getUserRoles()->toArray() as $role) {
                $user->removeUserRole($role);
            }

            foreach ($submittedRoles as $role) {
                $user->addUserRole($role);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
        ]);

        $resolver->setAllowedTypes('is_edit', 'bool');
    }
}
