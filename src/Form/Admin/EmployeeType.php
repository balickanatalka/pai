<?php

namespace App\Form\Admin;

use App\Entity\Employee;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First name',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last name',
            ])
            ->add('employeeNumber', TextType::class, [
                'label' => 'Employee number',
            ])
            ->add('department', TextType::class, [
                'label' => 'Department',
                'required' => false,
            ])
            ->add('position', TextType::class, [
                'label' => 'Position',
                'required' => false,
            ])
            ->add('hiredAt', DateTimeType::class, [
                'label' => 'Hired at',
                'widget' => 'single_text',
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
            ])
            ->add('appUser', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'placeholder' => 'Choose user',
                'required' => false,
                'query_builder' => function (UserRepository $repository) {
                    return $repository
                        ->createQueryBuilder('u')
                        ->orderBy('u.email', 'ASC');
                },
                'label' => 'User account',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
