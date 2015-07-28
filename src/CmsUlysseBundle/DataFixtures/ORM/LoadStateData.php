<?php

namespace CmsUlysseBundle\DataFixtures;

use CmsUlysseBundle\Entity\State;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Util\Inflector;

class LoadStateData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $states = array(
            array(
                'name' => 'En cours',
            ),
            array(
                'name' => 'Payée',
            ),
            array(
                'name' => 'Envoyée',
            ),
            array(
                'name' => 'Reçue',
            ),
        );

        $this->persistStates($states, $manager);
    }

    /**
     * @param array $stateDataArray
     * @param ObjectManager $manager
     */
    private function persistStates(array $stateDataArray, ObjectManager $manager)
    {
        foreach ($stateDataArray as $stateData) {
            $state = new State();

            foreach ($stateData as $fieldName => $fieldValue) {
                $setter = "set" . Inflector::classify($fieldName);
                $state->$setter($fieldValue);
            }

            $manager->persist($state);
            $this->addReference('state-' . $state->getName(), $state);
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
        return 7;
    }
}
