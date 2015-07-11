<?php

namespace CmsUlysseBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProductCategoriesData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $productCategories = array(
            array(
                'product' => $this->getReference('product-Iphone 1'),
                'category' => $this->getReference('category-Apple'),
            ),
            array(
                'product' => $this->getReference('product-Iphone 2'),
                'category' => $this->getReference('category-Apple'),
            ),
            array(
                'product' => $this->getReference('product-Iphone 3'),
                'category' => $this->getReference('category-Apple'),
            ),
            array(
                'product' => $this->getReference('product-Iphone 4'),
                'category' => $this->getReference('category-Apple'),
            ),
        );

        $this->persistProductCategory($productCategories, $manager);
    }

    /**
     * @param $productCategories
     * @param ObjectManager $manager
     */
    private function persistProductCategory($productCategories, ObjectManager $manager)
    {
        foreach ($productCategories as $associationData) {
            $product = $associationData['product'];
            $product->addCategory($associationData['category']);

            $manager->persist($product);
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
