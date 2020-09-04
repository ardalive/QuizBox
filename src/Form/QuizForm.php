<?php


namespace App\Form;


use App\Entity\Quiz;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,array $options){
        $builder
            ->add('name', TextType::class)
            ->add('Description', TextType::class)
            ->add('isActive', RadioType::class)
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Quiz::class,
        ));
    }
}