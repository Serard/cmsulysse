<?php

namespace CmsUlysseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CmsUlysseBundle\Entity\CategoryRepository;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CategoryRepository")
 */
class Category
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
     * @ORM\ManyToOne(targetEntity="Category")
     *
     * @ORM\JoinColumn(name="categ_up", referencedColumnName="id", nullable=true)
     */
    private $categ_up;

    public function __toString()
    {
        return $this->name;
    }

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
     * @return Category
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
     * Set categ_up
     *
     * @param Category $categUp
     * @return Category
     */
    public function setCategUp(Category $categUp = null)
    {
        $this->categ_up = $categUp;

        return $this;
    }

    /**
     * Get categ_up
     *
     * @return Category
     */
    public function getCategUp()
    {
        return $this->categ_up;
    }
}
