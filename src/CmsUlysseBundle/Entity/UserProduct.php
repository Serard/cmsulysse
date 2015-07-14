<?php

namespace CmsUlysseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UserProduct
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CmsUlysseBundle\Entity\UserProductRepository")
 */
class UserProduct
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
     * @ORM\ManyToOne(targetEntity="CmsUlysseBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="CmsUlysseBundle\Entity\Product", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="qty", type="integer")
     */
    private $qty;

    /**
     * @var boolean
     *
     * @ORM\Column(name="state", type="boolean"))
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity="CommandUserProduct", mappedBy="product", cascade={"persist"})
     */
    private $commands;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commands       = new ArrayCollection();
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
     * Set user
     *
     * @param \CmsUlysseBundle\Entity\User $product
     * @return UserProduct
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set product
     *
     * @param \CmsUlysseBundle\Entity\Product $product
     * @return UserProduct
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return UserProduct
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set qty
     *
     * @param integer $qty
     * @return UserProduct
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty
     *
     * @return integer
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set state
     *
     * @param boolean $state
     * @return UserProduct
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return boolean
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Add commands
     *
     * @param CommandUserProduct $commands
     */
    public function addCommand(CommandUserProduct $commands)
    {
        $this->commands[] = $commands;

        return $this;
    }

    /**
     * Remove commands
     *
     * @param CommandUserProduct $commands
     */
    public function removeCommand(CommandUserProduct $commands)
    {
        $this->commands->removeElement($commands);
    }

    /**
     *  Get commands
     *
     * @return Collection
     */
    public function getCommands()
    {
        return $this->commands;
    }
}
