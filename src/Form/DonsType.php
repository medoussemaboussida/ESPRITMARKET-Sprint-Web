<?php

namespace App\Form;

use App\Entity\Dons;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


use App\Entity\Evenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DonsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Autres champs de votre formulaire...
            ->add('idEv', EntityType::class, [
                'class' => Evenement::class,
                'choice_label' => 'nomEv',
            ])
            ->add('nbpoints', IntegerType::class, [
                'attr' => [
                    'max' => 100, // or any other value you want to limit the input to
                ],
            ])
            ->getForm();

            
        
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dons::class,
        ]);
    }}