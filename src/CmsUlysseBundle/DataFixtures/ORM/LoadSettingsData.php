<?php
namespace CmsUlysseBundle\DataFixtures\ORM;


use CmsUlysseBundle\Entity\Site;
use CmsUlysseBundle\Entity\Slider;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSettingsData  extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $settings = new Site();
        $settings
            ->setName('CmsUlysse')
            ->setSlogan('Qui a touché à mon code ? Ça marchait très bien vendredi')
            ->setPositionMenu(true)
            ->setThemeColor('#FFFFFF')
            ->setCorpsColonnes(1)
            ->setBestProduct(true)
            ->setCmActive(true)
            ->setSlider(true)
            ->setCommunityManagement('Qui a touché à mon code ? Ça marchait très bien hier');

        $slide = new Slider();
        $slide->setName('Super Slider');

        $manager->persist($settings);
        $manager->persist($slide);

        $manager->flush();
    }

    public function getOrder(){
        return '6';
    }
}