<?php

namespace CmsUlysseBundle\Form\Type;

use FOS\MessageBundle\FormFactory\NewThreadMessageFormFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Message form type for starting a new conversation
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class NewThreadMessageFormType extends NewThreadMessageFormFactory
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Destinataire', 'fos_user_username')
            ->add('Objet', 'text')
            ->add('Message', 'textarea');
    }
}
