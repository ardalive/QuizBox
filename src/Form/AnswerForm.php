<?php


namespace App\Form;


use App\Entity\Answers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerForm extends  AbstractType
{
    public function buildForm(FormBuilderInterface $builder,array $options){
        $builder
            ->add('btnDelete', ButtonType::class, [
                'label' => 'Delete',
                'attr'=>[
                    'class'=>'btn btn-lg btn-primary btnDel',
                    ],
                'row_attr' => [
                    'style' => 'display : inline-block; width : 20%;'
                ],
            ])
            ->add('AnswerBody', TextType::class, [
                'label'=> false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'input answer ...',
                    'class' => 'form-control answerBody'
                ],
                'row_attr' => [
                    'style' => 'width : 60%; display : inline-block'
                ],

            ])
            ->add('isTrue', RadioType::class, [
                'label' => 'right',
                'required' => false,
                'attr' => [
                    'class' => 'radioBtn',
                    'style' => 'margin-left : 50px;'
                ],
                'row_attr' => [
                    'style' => 'width : 20%; display : inline-block'
                ],

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