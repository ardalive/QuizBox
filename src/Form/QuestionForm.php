<?php


namespace App\Form;


use App\Entity\Questions;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,array $options){
        $builder

            ->add('QuestionBody', TextareaType::class, [
                'label'=> false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control description'
                ]])
            ->add('answers', CollectionType::class, [
                'entry_type' => AnswerForm::class,
                'entry_options' => ['label' => true],
                'label'=> false,
                'allow_add' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => 'radioBtn',
                ],
            ])
            ->add('save', SubmitType::class, [
                'attr'=>['class'=>'btn btn-lg btn-primary'],
                'label' => 'Save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Questions::class,
        ));
    }
}