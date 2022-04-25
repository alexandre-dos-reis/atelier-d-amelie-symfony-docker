<?php

namespace App\Form;

use App\Entity\ImageProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ImageProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageOriginalFile', VichFileType::class, [
                'label' => 'Image au format JPG ou PNG',
                'allow_delete' => false,
                'empty_data' => ''
            ])
            ->add('disposition', IntegerType::class, [
                'label' => 'Disposition',
                'empty_data' => ''
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImageProduct::class
        ]);
    }
}
