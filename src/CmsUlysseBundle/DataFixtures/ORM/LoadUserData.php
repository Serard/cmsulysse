<?php

namespace CmsUlysseBundle\DataFixtures\ORM;

use CmsUlysseBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    const PASSWORD = 'cmsulysse';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $users = array(
            array(
                'roles' => array('ROLE_USER', 'ROLE_ADMIN'),
                'email' => 'pauline.rdc@gmail.com',
                'username' => 'pauline.rdc@gmail.com',
                'lastname' => 'Rubin de Cervens',
                'firstname' => 'Pauline',
                'streetNumber' => '185',
                'address' => 'Avenue Victor Hugo',
                'administrativeArea' => 'Île-de-France',
                'postalCode' => '92140',
                'country' => 'France',
                'city' => 'Clamart',
                'tel' => '++33 897 89 78 97',
                'isActive' => true,
            ),array(
                'roles' => array('ROLE_USER'),
                'email' => 'pascalou@gmail.com',
                'username' => 'pascalou@gmail.com',
                'lastname' => 'Naviere',
                'firstname' => 'Pascal',
                'streetNumber' => '67',
                'address' => 'Rue d\'Aguesseau',
                'administrativeArea' => 'Île-de-France',
                'postalCode' => '92100',
                'country' => 'France',
                'city' => 'Boulogne-Bilancourt',
                'tel' => '+33 897 89 78 97',
                'isActive' => true,
            ),
            array(
                'roles' => array('ROLE_USER'),
                'email' => 'stephane.erard@free.fr',
                'username' => 'stephane.erard@free.fr',
                'lastname' => 'Erard',
                'firstname' => 'Stephane',
                'streetNumber' => '23',
                'address' => 'Avenue Jacques Jezequel',
                'administrativeArea' => 'Île-de-France',
                'postalCode' => '92170',
                'country' => 'France',
                'city' => 'Vanves',
                'tel' => '+33 897 89 78 97',
                'isActive' => true,
            ),
        );

        foreach($users as $data){
            $user = new User();

            foreach ($data as $fieldName => $fieldValue) {
                if ($fieldName == 'password') {
                    continue;
                }

                if ($fieldName == 'addNeighbourhood') {
                    foreach ($fieldValue as $neighbourhood) {
                        $user->$fieldName($neighbourhood);
                    }
                } else {
                    $setter = sprintf('set%s', Inflector::classify($fieldName));
                    $user->$setter($fieldValue);
                }
            }

            $encoder = $this->container
                ->get('security.encoder_factory')
                ->getEncoder($user);

            $user->setConfirmationToken(hash('sha256', uniqid()));
            $user->setSalt(hash('sha256', uniqid()));

            $password = array_key_exists('password', $data) ? $data['password'] : self::PASSWORD;

            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $manager->persist($user);
            $this->addReference($user->getEmail(), $user);

            $manager->flush();
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
