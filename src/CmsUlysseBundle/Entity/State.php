<?php

namespace CmsUlysseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * State
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CmsUlysseBundle\Entity\StateRepository")
 */
class State
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="state", cascade={"persist"})
     */
    private $commands;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return State
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param $commands
     * @return $this
     */
    public function setSpecifications(ArrayCollection $commands)
    {
        $this->commands = $commands;

        return $this;
    }
}
