<?php

namespace CmsUlysseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Command
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CmsUlysseBundle\Entity\CommandRepository")
 */
class Command
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
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="postalcode", type="integer")
     */
    private $postalcode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sendAt", type="datetime")
     */
    private $sendAt;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", precision = 2)
     */
    private $amount;

    /**
    * @var State
    *
    * @ORM\ManyToOne(targetEntity="State" ,inversedBy="state")
    * @ORM\JoinColumn(name="state_id", referencedColumnName="id")
    */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity="CommandUserProduct", mappedBy="command", cascade={"persist"})
     */
    private $commandUserProducts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Command
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Command
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Command
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->adress;
    }

    /**
     * Set postalcode
     *
     * @param integer $postalcode
     * @return Command
     */
    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    /**
     * Get postalcode
     *
     * @return integer 
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Command
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set sendAt
     *
     * @param \DateTime $sendAt
     * @return Command
     */
    public function setSendAt($sendAt)
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    /**
     * Get sendAt
     *
     * @return \DateTime 
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Command
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param State $state
     * @return $this
     */
    public function setState(State $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Add products
     *
     * @param CommandUserProduct $products
     * @return Command
     */
    public function addProduct(CommandUserProduct $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param CommandUserProduct $products
     */
    public function removeProduct(CommandUserProduct $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

}
