<?php

namespace App\Form;

use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Utilisation de FileType pour l'upload d'images
use Symfony\Component\Validator\Constraints\NotBlank; // Import de NotBlank



class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('titrePublication', TextType::class, [
            'label' => 'Titre de la Publication',
            'constraints' => [
                new NotBlank(['message' => 'Veuillez entrer un titre de publication']),
            ],
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Titre de la publication',
            ],
        ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer la description']),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Description de la publication',
                ],
            ])
            ->add('imagePublication', FileType::class, [ // Champ pour l'upload de l'image
                'label' => 'Image de la Publication',
                'mapped' => false, // Ne pas mapper ce champ à l'entité
                'required' => false, // Rendre le champ facultatif
                
            ])
            // Champ caché pour datePublication
            ->add('datePublication', HiddenType::class, [
                'mapped' => false, // Ne pas mapper ce champ à l'entité
            ])
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
