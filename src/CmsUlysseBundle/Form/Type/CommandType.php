<?php
namespace CmsUlysseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class CommandType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', null, array('label' => 'Nom'))
            ->add('firstname', null, array('label' => 'Prénom'))
            ->add('address', null, array('label' => 'Adresse'))
            ->add('postal_code', null, array('label' => 'Code postal'))
            ->add('city', null, array('label' => 'Ville'))
            ->add('tel', null, array('label' => 'Téléphone'))
            ->add('btn', 'submit', array('label' => 'Payer'))
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
        return 'user_form';
    }
}
