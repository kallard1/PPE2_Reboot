<?php

namespace App\Form;

use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent', EntityType::class, [
                'multiple' => false,
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('p')
                        ->where('p.status = true')
                        ->where('p.id != 1')
                        ->addOrderBy('p.id', 'ASC');
                    return $qb;
                },
                'label' => 'category.form.parent',
                'required' => false,
                'expanded' => false
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'category.form.name',
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'category.form.description',
                ]
            ])
            ->add('status', CheckboxType::class, [
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
