<?php

namespace App\Form;

use App\Entity\MiniTwits;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class MiniTwitsType extends AbstractType
{
    private $formPrefix;

    public function __construct()
    {
        $this->formPrefix = 0;
    }
    public function getBlockPrefix()
    {
        return $this->formPrefix . '-' . parent::getBlockPrefix();
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        ++$this->formPrefix;
        $builder
            ->add('title', TextType::class,[
                'required' => false,
            ])
            ->add('description')
            ->add('isPublic',CheckboxType::class,[
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MiniTwits::class,
        ]);
    }
}
