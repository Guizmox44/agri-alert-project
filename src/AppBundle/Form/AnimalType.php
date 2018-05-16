<?php

namespace AppBundle\Form;

use AppBundle\Entity\Breed;
use AppBundle\Entity\Species;
use AppBundle\Repository\BreedRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $request = $options['request'];
        $doctrine = $options['doctrine'];

        $urlTag = $request->get('tag');

        $getTag = $doctrine->getRepository(Species::class)->findIdSpecies($urlTag);

        $tag = $getTag['id'];

        $builder
            ->add('earTag', null,['label' => 'Numéro de boucle'])
            ->add('birthDate', null,['label' => 'Date de naissance','format'=> 'dd MM yyyy'])
            ->add('slaughterAt', null, ['label' => 'Date de sortie','format'=> 'dd MM yyyy'])
            ->add('breed', EntityType::class, [
                'class' => Breed::class,
                'choice_label' => 'wording',
                'multiple' => false,
                'label' => 'Race',
                'query_builder' => function(BreedRepository $repository) use($tag) {
                    return $repository->getBreedBySpecies($tag);
                }
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Animal'
        ));
        $resolver->setRequired('request');
        $resolver->setRequired('doctrine');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_animal';
    }


}
