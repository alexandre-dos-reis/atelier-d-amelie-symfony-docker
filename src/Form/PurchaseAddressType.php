<?php

namespace App\Form;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;
use App\Service\PurchaseAddress\PurchaseAddressInterface;
use Symfony\Component\Form\FormEvent;

class PurchaseAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('email', Type\EmailType::class, ['label' => 'Email', 'attr' => [
                'placeholder' => 'jean.dupont@mail.fr'
            ]])

            ->add('firstAddressFullname', Type\TextType::class, ['label' => 'Nom complet', 'attr' => [
                'placeholder' => 'Jean Dupont'
            ]])
            ->add('firstAddressAddress', Type\TextareaType::class, ['label' => 'Adresse', 'attr' => [
                'placeholder' => '1 rue du moulin à vent'
            ]])
            ->add('firstAddressPostalCode', Type\TextType::class, ['label' => 'Code postal', 'attr' => [
                'placeholder' => '75001'
            ]])
            ->add('firstAddressCity', Type\TextType::class, ['label' => 'Ville', 'attr' => [
                'placeholder' => 'Paris'
            ]])
            ->add('firstAddressCountry', Type\CountryType::class, ['label' => 'Pays'])
            ->add('firstAddressPhone', Type\TelType::class, ['label' => 'Téléphone', 'attr' => [
                'placeholder' => '0102030405'
            ]])

            // Choose between :
            // - one address for both delivery and billing
            // - one address for each
            ->add('hasBillingAddress', Type\CheckboxType::class, ['label' => 'Ajouter une adresse de livraison ?'])

            // if Delivery address is choosen above then the billing Address is mandatory.
            ->add('secondAddressFullname', Type\TextType::class, ['label' => 'Nom complet', 'attr' => [
                'placeholder' => 'Jean Dupont'
            ]])
            ->add('secondAddressAddress', Type\TextareaType::class, ['label' => 'Adresse', 'attr' => [
                'placeholder' => '1 rue du moulin à vent'
            ]])
            ->add('secondAddressPostalCode', Type\TextType::class, ['label' => 'Code postal', 'attr' => [
                'placeholder' => '75001'
            ]])
            ->add('secondAddressCity', Type\TextType::class, ['label' => 'Ville', 'attr' => [
                'placeholder' => 'Paris'
            ]])
            ->add('secondAddressCountry', Type\CountryType::class, [
                'label' => 'Pays'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PurchaseAddressInterface::class,
            'validation_groups' => function (FormInterface $form) {
                return $form->getData()->getHasBillingAddress() ? ['Default', 'hasBillingAddress'] : ['Default'];
            },
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
