<?php
namespace CmsUlysseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SliderType extends  AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return  $builder
            ->add('name', null, array(
                'attr' => array('class' => 'form-control')))
            ->add('pictures', 'collection', array(
                'type' => new PictureType(),
                'allow_add' => true,
                'by_reference' => false,
            ))
            ->add('btn', 'submit', array(
                'label' => 'Valider',
                'attr' => array('class' => 'btn btn-primary'))
            )
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => 'CmsUlysseBundle\Entity\Slider',
            'cascade_validation' => true,
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'slider_type';
    }
}