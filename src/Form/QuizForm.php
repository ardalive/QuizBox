<?php


namespace App\Form;


use App\Entity\Quiz;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                    'class'=>'form-control'
                ]])
            ->add('Description', TextType::class, [
                'label'=> false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('isActive', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'yes' => 'yes',
                    'no' => 'no',
                ],
                'attr'=>['class'=>'radioBtns'],
                'expanded' => true])
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