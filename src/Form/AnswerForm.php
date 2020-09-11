<?php


namespace App\Form;


use App\Entity\Answers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerForm extends  AbstractType
{
    public function buildForm(FormBuilderInterface $builder,array $options){
        $builder
            ->add('AnswerBody', TextType::class, [
                'label'=> false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'input answer ...',
                    'class' => 'form-control answerBody'
                ]])
            ->add('isTrue', RadioType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'radioBtn',
                ],
                'data' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Answers::class,
        ));
    }
}