<?php

namespace App\Form;

use App\Entity\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('bio')
            ->add('avatar', FileType::class,[
                'label'=>'Your Profile Image(JPG or PNG file)',
                'mapped'=>false,
                'required'=>false,
                'constraints'=>[
                    new File([
                        'maxSize'=>'3072K',
                        'mimeTypes'=>[
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage'=>'Please upload PNG or JPEG file'
                    ])
                ]
            ])
            ->add('location')
            ->add('website')
            ->add('birthDate', DateType::class,[
                'widget'=>'single_text',
                'required'=>false,
            ])
            ->add('backgroundImage', FileType::class,[
                'label'=>'Your Background Image(JPG or PNG file)',
                'mapped'=>false,
                'required'=>false,
                'constraints'=>[
                    new File([
                        'maxSize'=>'3072K',
                        'mimeTypes'=>[
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage'=>'Please upload PNG or JPEG file'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
