<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\product;
use App\Entity\VatRate;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'product.form.name',
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'product.form.description',
                ]
            ])
            ->add('sku', TextType::class, [
                'attr' => [
                    'placeholder' => 'product.form.sku',
                ]
            ])
            ->add('categories', EntityType::class, [
                'multiple' => true,
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('p')
                        ->where('p.status = true')
                        ->addOrderBy('p.id', 'ASC');
                    return $qb;
                },
                'label' => 'product.form.categories',
                'required' => true,
                'expanded' => false
            ])
            ->add('vatRate', EntityType::class, [
                'multiple' => false,
                'class' => VatRate::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('p')
                        ->addOrderBy('p.id', 'ASC');
                    return $qb;
                },
                'label' => 'product.form.vat_rate',
                'required' => true,
                'expanded' => false
            ])
            ->add('price', NumberType::class, [
                'attr' => [
                    'placeholder' => 'product.form.price',
                ]
            ])
            ->add('stock', TextType::class, [
                'attr' => [
                    'placeholder' => 'product.form.stock',
                ]
            ])
            ->add('promotion', TextType::class, [
                'attr' => [
                    'placeholder' => 'product.form.promotion',
                ]
            ])
            ->add('status', CheckboxType::class, [
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
