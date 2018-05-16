<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotBlankValidator;


class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['reset_password']) {

            $builder->add('email')
                ->add('lastName', null, array(
                    'empty_data' => 'default',
                ))
                ->add('siret', null, array(
                    'empty_data' => '12345678912345',
                ))
                ->add('username', null, array(
                    'empty_data' => 'default',
                ))
                ->add('numberLivestock', null, array(
                    'empty_data' => '12345678',
                ))
                ->add('city', null, array(
                    'empty_data' => 'default',
                ))
                ->add('address', null, array(
                    'empty_data' => 'default',
                ))
                ->add('zipCode', null, array(
                    'empty_data' => '12345',
                ))
                ->add('password', null, array(
                    'empty_data' => 'aA111111--',
                ));
        }
        else {
            $builder
                ->add('username', null, [
                    'label' => 'Prénom'
                ])
                ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                    $user = $event->getData();
                    $form = $event->getForm();
                    // Nouveau user ?
                    if (null === $user->getId()) {
                        $form->add('password', RepeatedType::class, [
                            'type' => PasswordType::class,
                            'first_options' => ['label' => 'Mot de passe '],
                            'second_options' => ['label' => 'Confirmer le mot de passe'],
                            'invalid_message' => 'Les mots de passe ne correspondent pas',
                        ]);
                    } else {
                        // User existant
                        $form->add('password', RepeatedType::class, [
                            'type' => PasswordType::class,
                            'options' => [
                                'attr' => ['class' => 'password-field'],
                                'attr' => ['placeholder' => 'Laissez vide si inchangé']
                            ],
                            'first_options' => ['label' => 'Mot de passe'],
                            'second_options' => ['label' => 'Confirmer le mot de passe'],
                            'invalid_message' => 'Les mots de passe ne correspondent pas',
                        ]);
                    }
                })
                ->add('email')
                ->add('lastName', null, [
                    'label' => 'Nom',
                ])
                ->add('siret', null, [
                ])
                ->add('numberLivestock', null, [
                    'label' => 'Numero de cheptel',
                ])
                ->add('address', null, [
                    'label' => 'Adresse',
                ])
                ->add('zipCode', null, [
                    'label' => 'Code postal',
                ])
                ->add('city', null, [
                    'label' => 'Ville',
                ])
                ->add('agreeTerms', CheckboxType::class, array('mapped' => false,
                    'label' => ' J\' accepte les conditions générales d\'utilisation',
                    'constraints' => array(new NotBlank(array('message' => 'Veuillez accepter les conditions générales d\'utilisation'))
                    )));
        }
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'attr' => ['novalidate' => 'novalidate'],
            'reset_password' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
