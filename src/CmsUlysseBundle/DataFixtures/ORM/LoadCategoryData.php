<?php

namespace CmsUlysseBundle\DataFixtures;

use CmsUlysseBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Util\Inflector;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $parentCategories = array(
            array(
                'name' => 'Numérique'
            ),
            array(
                'name' => 'Apple'
            ),
            array(
                'name' => 'Livre'
            ),
            array(
                'name' => 'Jeux Vidéos'
            )
        );

        $this->persistGoodCategories($parentCategories, $manager);

        $childCategories = array(
            array(
                'name' => 'Téléphone',
                'categUp' => $this->getReference('category-Numérique'),
            ),
            array(
                'name' => 'Ordinateur',
                'categUp' => $this->getReference('category-Numérique'),
            ),
            array(
                'name' => 'Mp3',
                'categUp' => $this->getReference('category-Numérique'),
            ),
            array(
                'name' => 'Appareil Photo',
                'categUp' => $this->getReference('category-Numérique'),
            ),
            array(
                'name' => 'Divers',
                'categUp' => $this->getReference('category-Numérique'),
            ),
            array(
                'name' => 'Iphone',
                'categUp' => $this->getReference('category-Apple'),
            ),
            array(
                'name' => 'Ipod',
                'categUp' => $this->getReference('category-Apple'),
            ),
            array(
                'name' => 'Ipad',
                'categUp' => $this->getReference('category-Apple'),
            ),
            array(
                'name' => 'Macbook',
                'categUp' => $this->getReference('category-Apple'),
            ),
            array(
                'name' => 'Macbook Air',
                'categUp' => $this->getReference('category-Apple'),
            ),
            array(
                'name' => 'Macbook Pro',
                'categUp' => $this->getReference('category-Apple'),
            ),
            array(
                'name' => 'Romans',
                'categUp' => $this->getReference('category-Livre'),
            ),
            array(
                'name' => 'Science Fiction',
                'categUp' => $this->getReference('category-Livre'),
            ),
            array(
                'name' => 'Histoire',
                'categUp' => $this->getReference('category-Livre'),
            ),
            array(
                'name' => 'Art',
                'categUp' => $this->getReference('category-Livre'),
            ),
            array(
                'name' => 'Policier',
                'categUp' => $this->getReference('category-Livre'),
            ),
            array(
                'name' => 'Sciences',
                'categUp' => $this->getReference('category-Livre'),
            ),
            array(
                'name' => 'Informatique',
                'categUp' => $this->getReference('category-Livre'),
            ),
            array(
                'name' => 'Xbox One',
                'categUp' => $this->getReference('category-Jeux Vidéos'),
            ),
            array(
                'name' => 'Xbox 360',
                'categUp' => $this->getReference('category-Jeux Vidéos'),
            ),
            array(
                'name' => 'Super Nintendo',
                'categUp' => $this->getReference('category-Jeux Vidéos'),
            ),
            array(
                'name' => 'Wii',
                'categUp' => $this->getReference('category-Jeux Vidéos'),
            ),
            array(
                'name' => 'PlayStation',
                'categUp' => $this->getReference('category-Jeux Vidéos'),
            )
        );

        $this->persistGoodCategories($childCategories, $manager);
    }

    /**
     * @param array         $categoryDataArray
     * @param ObjectManager $manager
     */
    private function persistGoodCategories (array $categoryDataArray, ObjectManager $manager)
    {
        foreach ($categoryDataArray as $goodCategoryData) {
            $goodCategory = new Category();
            foreach ($goodCategoryData as $fieldName => $fieldValue) {
                $setter = "set" . Inflector::classify($fieldName);
                $goodCategory->$setter($fieldValue);
            }
            $manager->persist($goodCategory);
            $this->addReference('category-' . $goodCategory->getName(), $goodCategory);
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
        return 2;
    }
}
