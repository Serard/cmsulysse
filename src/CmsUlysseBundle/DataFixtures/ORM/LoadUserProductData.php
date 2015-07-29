<?php

namespace CmsUlysseBundle\DataFixtures\ORM;

use CmsUlysseBundle\Entity\UserProduct;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserProductData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $UserProducts = array(
            array(
                'product' => $this->getReference('product-Iphone 1'),
                'user' => $this->getReference('stephane.erard@free.fr'),
                'price' => 200,
                'qty' => 50,
                'state' => false,
            ),
            array(
                'product' => $this->getReference('product-Iphone 2'),
                'user' => $this->getReference('stephane.erard@free.fr'),
                'price' => 300,
                'qty' => 11,
                'state' => false,
            ),
            array(
                'product' => $this->getReference('product-Iphone 3'),
                'user' => $this->getReference('stephane.erard@free.fr'),
                'price' => 500,
                'qty' => 6,
                'state' => true,
            ),
            array(
                'product' => $this->getReference('product-Iphone 4'),
                'user' => $this->getReference('stephane.erard@free.fr'),
                'price' => 650,
                'qty' => 4,
                'state' => true,
            ),
            array(
                'product' => $this->getReference('product-Iphone 5 S'),
                'user' => $this->getReference('stephane.erard@free.fr'),
                'price' => 200,
                'qty' => 0,
                'state' => true,
            ),
            array(
                'product' => $this->getReference('product-Iphone 6'),
                'user' => $this->getReference('stephane.erard@free.fr'),
                'price' => 800,
                'qty' => 18,
                'state' => true,
            ),
            array(
                'product' => $this->getReference('product-Un amour de jeunesse'),
                'user' => $this->getReference('pascalou@gmail.com'),
                'price' => 19.99,
                'qty' => 18,
                'state' => true,
            ),
            array(
                'product' => $this->getReference('product-Les filles au chocolat'),
                'user' => $this->getReference('pascalou@gmail.com'),
                'price' => 16,
                'qty' => 2,
                'state' => true,
            ),

            array(
                'product' => $this->getReference('product-La boite à musique'),
                'user' => $this->getReference('pascalou@gmail.com'),
                'price' => 11,
                'qty' => 6,
                'state' => true,
            ),
            array(
                'product' => $this->getReference('product-La boite à musique'),
                'user' => $this->getReference('stephane.erard@free.fr'),
                'price' => 15,
                'qty' => 80,
                'state' => true,
            ),
            array(
                'product' => $this->getReference('product-Un amour de jeunesse'),
                'user' => $this->getReference('stephane.erard@free.fr'),
                'price' => 15,
                'qty' => 80,
                'state' => true,
            ),
        );

        $this->persistUserProducts($UserProducts, $manager);
    }

    /**
     * @param $userProducts
     * @param ObjectManager $manager
     */
    private function persistUserProducts($userProducts, ObjectManager $manager)
    {
        foreach ($userProducts as $userProductsData) {
            $userProduct = new UserProduct();
            foreach ($userProductsData as $fieldName => $fieldValue) {
                $setter = "set" . Inflector::classify($fieldName);
                $userProduct->$setter($fieldValue);
            }
            $manager->persist($userProduct);
        }
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }
}
