<?php

namespace CmsUlysseBundle\Form\Type;

use CmsUlysseBundle\CmsUlysseBundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Nom : '))
            ->add('categ_up', 'entity', array('label'    => 'Catégorie mère : ',
                'required' => false,
                'class'    => 'CmsUlysseBundle:Category',
                'property' => 'name',
                'query_builder' => function (\CmsUlysseBundle\Entity\CategoryRepository $r) {
                    return $r->findCategsUpForm();
                }))
            ->add('btn', 'submit', array('label' => 'Envoyer'));

        return $builder;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsUlysseBundle\Entity\Category',
        ));

        return $resolver;
    }

    public function getName()
    {
        return 'category_form';
    }
}