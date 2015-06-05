<?php

namespace CmsUlysseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="Price", type="float")
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="Qty", type="integer")
     */
    private $qty;


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
     * Set userId
     *
     * @param \CmsUlysseBundle\Entity\User|int $userId
     * @return UserProduct
     */
    public function setUser(User $userId)
    {
        $this->user = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
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
     * @param boolean $qty
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
     * @return boolean 
     */
    public function getQty()
    {
        return $this->qty;
    }
}
