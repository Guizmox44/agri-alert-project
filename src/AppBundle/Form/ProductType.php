<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label',null,['label' => 'Nom'])
            ->add('quantity',null,['label' => 'Quantité'])
            ->add('unit',null,['label' => 'Unité'])
            ->add('expiryDate',null,['label' => 'Date d\'expiration',
                                                 'format'=> 'd MM yyyy'])
            ->add('alert',null,['label' => 'Alerte'])
            ->add('category',null,['choice_label' => 'label','label' => 'Categorie','constraints'=> new NotBlank()])
            ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product',
            'attr' => ['novalidate' => 'novalidate'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


}
