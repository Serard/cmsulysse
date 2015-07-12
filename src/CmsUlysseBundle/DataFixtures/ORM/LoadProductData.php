<?php

namespace CmsUlysseBundle\DataFixtures\ORM;

use CmsUlysseBundle\Entity\Product;
use CmsUlysseBundle\Entity\Specification;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProductData  extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $products = array(
            array(
                'name' => 'Iphone 1',
                'description' => 'Smartphone de haute gamme',
                'valid' => false,
            ),
            array(
                'name' => 'Iphone 2',
                'description' => 'Smartphone de haute gamme',
                'valid' => false,
            ),
            array(
                'name' => 'Iphone 3',
                'description' => 'Smartphone de haute gamme',
                'valid' => false,
            ),
            array(
                'name' => 'Iphone 4',
                'description' => 'Smartphone de haute gamme',
                'valid' => false,
            ),
            array(
                'name' => 'Iphone 5 S',
                'description' => 'Smartphone de haute gamme',
                'valid' => false,
            ),
            array(
                'name' => 'Iphone 6',
                'description' => 'Smartphone de haute gamme, avec un appareil photo
                 de haute qualité et un écran incurvé pour une meilleure prise en main',
                'valid' => false,
            ),
            array(
                'name' => 'Un amour de jeunesse',
                'description' => "d'après l'histoire de maxence et margot",
                'valid' => false,
            ),
            array(
                'name' => 'Les filles au chocolat',
                'description' => "Tome 6 => Coeur Cookue",
                'valid' => false,
            ),
            array(
                'name' => 'La boite à musique',
                'description' => "de Mary Higgins Clark",
                'valid' => false,
            ),

        );

        $this->persistProduct($products, $manager);

        $specifications = array(
            array(
                'name' => 'Dimensions',
                'content' => '115 x 61 x 11,6 mm',
                'product' => $this->getReference('product-Iphone 1')
            ),
            array(
                'name' => 'Poids',
                'content' => '135 grammes',
                'product' => $this->getReference('product-Iphone 1')
            ),
            array(
                'name' => 'Taille Ecran',
                'content' => '3,5 pouces',
                'product' => $this->getReference('product-Iphone 1')

            ),
            array(
                'name' => 'Résolution Ecran',
                'content' => '320 x 480 px',
                'product' => $this->getReference('product-Iphone 1')
            ),
            array(
                'name' => 'Repertoire',
                'content' => 'carte SIM = 1000',
                'product' => $this->getReference('product-Iphone 1')
            ),
            array(
                'name' => 'Bluetooth',
                'content' => 'Bluetooth 2.0',
                'product' => $this->getReference('product-Iphone 1')
            ),
            array(
                'name' => 'Mémoire Interne',
                'content' => '4 à 8 Go',
                'product' => $this->getReference('product-Iphone 1')
            )
        );

        $this->persistSpecification($specifications, $manager);

    }

    /**
     * @param array $productDataArray
     * @param ObjectManager $manager
     */
    private function persistProduct(array $productDataArray, ObjectManager $manager)
    {
        foreach ($productDataArray as $productData) {
            $product = new Product();

            foreach ($productData as $fieldName => $fieldValue) {
                $setter = "set" . Inflector::classify($fieldName);
                $product->$setter($fieldValue);
            }

            $manager->persist($product);
            $this->addReference('product-' . $product->getName(), $product);
        }
        $manager->flush();
    }

    /**
     * @param array $specDataArray
     * @param ObjectManager $manager
     */
    private function persistSpecification(array $specDataArray, ObjectManager $manager)
    {
        foreach ($specDataArray as $specData) {

            $specification = new Specification();

            foreach ($specData as $fieldName => $fieldValue) {
                $setter = "set" . Inflector::classify($fieldName);
                $specification->$setter($fieldValue);
            }

            $manager->persist($specification);
            $this->addReference('specification-' . $specification->getName(), $specification);
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
        return 3;
    }
}
