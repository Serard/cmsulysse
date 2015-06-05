<?php

namespace CmsUlysseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('slogan')
            ->add('file')
            ->add('theme_color')
            ->add('position_menu','checkbox', array('required' => false))
            ->add('corps_colonnes','choice',array(
                'choices'   => array(
                    '1' => '1 colonnes',
                    '2' => '2 colonnes',
                    '3' => '3 colonnes')))
            ->add('btn', 'submit', array('label' => 'Valider'));

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
        return 'site_form';
    }
}