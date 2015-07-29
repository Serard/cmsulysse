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
            ->add('body_color')
            ->add('header_color')
            ->add('icone_color')
            ->add('footer')
            ->add('position_menu','choice', array(
                'choices' => array(
                    '0' => 'haut de la page',
                    '1' => 'à droite de la page'),
                'attr' => array(
                    'class' => "form-control"
                )))
            ->add('corps_colonnes','choice',array(
                'choices'   => array(
                    '1' => '1 colonne (centrale)',
                    '2' => '2 colonnes (gauche et centrale)',
                    '3' => '2 colonnes (droite et centrale)',
                    '4' => '3 colonnes (gauche droite et centrale)'),
                'attr' => array(
                    'class' => "form-control"
                )))
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
        return 'site_form';
    }
}