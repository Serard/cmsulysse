<?php
namespace CmsUlysseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends  AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return  $builder
            ->add('name')
            ->add('description')
            ->add('file')
            ->add('categories', 'entity', array('label'    => 'Catégories : ',
                'required' => true,
                'class'    => 'CmsUlysseBundle:Category',
                'property' => 'name',
                'query_builder' => function (\CmsUlysseBundle\Entity\CategoryRepository $r) {
                    return $r->findCategsDownForm();
                },
                'multiple' => true))
            ->add('specifications', 'collection', array(
                'type' => new SpecificationType(),
                'allow_add' => true,
                'by_reference' => false,
                ))
            ->add('btn', 'submit', array('label' => 'Valider'))
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        return $resolver->setDefaults(array(
            'data_class' => 'CmsUlysseBundle\Entity\Product',
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
        return 'product_type';
    }
}