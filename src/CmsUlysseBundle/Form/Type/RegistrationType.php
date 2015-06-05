<?php
namespace CmsUlysseBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RegistrationType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('label' => 'Email'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
                'required' => false
            ))
            ->add('lastname', null, array('label' => 'Nom'))
            ->add('firstname', null, array('label' => 'Prénom'))
            ->add('street_number')
            ->add('address', null, array('label' => 'Adresse'))
            ->add('city', null, array('label' => 'City'))
            ->add('administrative_area')
            ->add('postal_code', null, array('label' => 'CP'))
            ->add('country')
            ->add('tel', null, array('label' => 'Téléphone'))
            ->add('is_active', null, array(
                'data' => '1'
            ))
        ;

        return $builder;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsUlysseBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'ulysse_user_registration';
    }

}
