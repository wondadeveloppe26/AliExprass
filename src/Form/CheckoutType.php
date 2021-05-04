<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['User'];

        $builder
            ->add('addresse', EntityType::class, [
                'class' => Address::class,
                'required' => true,
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true
            ])
            ->add('carrier', EntityType::class, [
                'class' => Carrier::class,
                'required' => true,
                'multiple' => false,
            ])
            ->add('informations', TextareaType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => array(),
        ]);
    }
}
