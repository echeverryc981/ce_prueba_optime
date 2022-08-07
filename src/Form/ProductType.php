<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, array('label'=>'Código', 'required'=>'required', 'attr'=>array('class'=>'form-control')))
            ->add('name', TextType::class, array('label'=>'Nombre', 'required'=>'required', 'attr'=>array('class'=>'form-control')))
            ->add('description',TextareaType::class, array('label'=>'Descripción', 'required'=>'required', 'attr'=>array('class'=>'form-control')))
            ->add('brand', TextType::class, array('label'=>'Marca', 'required'=>'required', 'attr'=>array('class'=>'form-control')))
            ->add('price', MoneyType::class, array('label'=>'Precio', 'required'=>'required', 'attr'=>array('class'=>'form-control')))
            ->add('Category', TextType::class,  array('label'=>'Category', 'required'=>'required', 'attr'=>array('class'=>'form-control')))
            ->add('submit', SubmitType::class, array('label'=>'submit',  'attr'=>array('class'=>'btn btn-primary')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
