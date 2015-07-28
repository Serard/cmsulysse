<?php

namespace CmsUlysseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SpecificationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array(
            'attr' => array('class'=>'form-control'),
                'label_attr' => array('class' => 'col-sm-2 control-label'))
        );
        $builder->add('content',null, array(
            'attr' => array('class'=>'form-control'),
            'label_attr' => array('class' => 'col-sm-2 control-label'))
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsUlysseBundle\Entity\Specification',
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'form_specification';
    }
}