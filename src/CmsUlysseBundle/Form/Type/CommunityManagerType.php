<?php

namespace CmsUlysseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommunityManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('community_management', null, array(
                'label' => false,
                'attr' => array('class' => 'form-control')))
            ->add('btn', 'submit', array(
                'label' => 'Valider',
                'attr' => array('class' => 'btn btn-primary')
            ));
        return $builder;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsUlysseBundle\Entity\Site',
        ));

        return $resolver;
    }

    public function getName()
    {
        return 'cm_form';
    }
}