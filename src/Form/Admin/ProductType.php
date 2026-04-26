<?php

namespace App\Form\Admin;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Brand;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(max: 100),
                ],
            ])

            ->add('price', NumberType::class, [
                'label' => 'Price',
                'scale' => 2,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\PositiveOrZero(),
                ],
            ])

            ->add('stockQuantity', IntegerType::class, [
                'label' => 'Stock quantity',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\PositiveOrZero(),
                ],
            ])

            ->add('model', TextType::class, [
                'label' => 'Model',
                'required' => false,
            ])

            ->add('brandName', TextType::class, [
                'label' => 'Brand name',
                'required' => false,
            ])

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Select category',
                'required' => false,
            ])

            ->add('brand', EntityType::class, [
                'class' => Brand::class,
                'choice_label' => 'name',
                'placeholder' => 'Select brand',
                'required' => false,
            ])

            ->add('manufacturer', EntityType::class, [
                'class' => Brand::class,
                'choice_label' => 'name',
                'placeholder' => 'Select manufacturer',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
