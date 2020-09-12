<?php

namespace App\Form;

use App\Entity\Questions;
use App\Entity\Quiz;
use App\Service\choicegenerator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,array $options){
        $builder
            ->add('name', TextType::class, [
                'label'=> false,
                'required' => true,
                'attr'=>[
                    'class'=>'form-control name'
                ]])
            ->add('Description', TextareaType::class, [
                'label'=> false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control description'
                ]])
            ->add('isActive', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'yes' => true,
                    'no' => false,
                ],
                'attr'=>[
                    'class'=>'radioBtns',
                ],
                'expanded' => true])
            ->add('questionID', EntityType::class, [
                'class' => Questions::class,
                'multiple' => true,
            ])
            ->add('save', SubmitType::class, [
                'attr'=>['class'=>'btn btn-lg btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Quiz::class,
        ));
    }


}