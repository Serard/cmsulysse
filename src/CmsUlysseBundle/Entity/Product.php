<?php

namespace CmsUlysseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="CmsUlysseBundle\Entity\ProductRepository")
 */
class Product
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
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Specification", mappedBy="product", cascade={"persist"})
     */
    private $specifications;

    /**
     * @ORM\ManyToMany(targetEntity="Category")
     * @ORM\JoinTable(name="product_category",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="UserProduct", mappedBy="product", cascade={"persist"})
     */
    private $usersProducts;

    /**
     * @ORM\OneToMany(targetEntity="Picture", mappedBy="product", cascade={"persist"})
     */
    private $pictures;

    /**
     * @var bool
     *
     * @ORM\Column(name="valid", type="boolean")
     */
    private $valid;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->usersProducts = new ArrayCollection();
        $this->specifications = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
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
     * @return Product
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
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getSpecifications()
    {
        return $this->specifications;
    }

    public function addSpecification(Specification $specification)
    {
        $this->specifications[] = $specification;
    }

    public function removeSpecification(Specification $specification)
    {
        $this->specifications->removeElement($specification);
    }

    public function addPicture(Picture $picture)
    {
        $this->pictures[] = $picture;
    }

    /**
     * @return mixed
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * @param $pictures
     * @return $this
     */
    public function setPictures($pictures)
    {
        $this->pictures = $pictures;

        return $this;
    }

    public function removePicture(Picture $picture)
    {
        $this->pictures->removeElement($picture);
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;
    }

    /**
     * Remove Categories
     *
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get Categories
     *
     * @return Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param UserProduct $userProduct
     */
    public function addUserProduct(UserProduct $userProduct)
    {
        $this->usersProducts[] = $userProduct;
    }

    /**
     * Remove UserProducts
     *
     * @param UserProduct $userProduct
     */
    public function removeUserProduct(UserProduct $userProduct)
    {
        $this->usersProducts->removeElement($userProduct);
    }

    /**
     * Get UserProducts
     *
     * @return Collection
     */
    public function getUserProducts()
    {
        return $this->usersProducts;
    }

    /**
     * @return $valid
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @param $valid
     * @return Product
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    public function isValid()
    {
        return $this->valid;
    }

    public function getMinPrice()
    {
        $minPrice = 0;

        foreach($this->usersProducts as $userProduct){
            if($minPrice === 0 || $userProduct->getPrice()>$minPrice){
                $minPrice = $userProduct->getPrice();
                $id = $userProduct->getId();
                $Qty = $userProduct->getQty();
                $user = $userProduct->getUser();
            }
        }

        return array(
            'price'=>$minPrice,
            'id'=>$id,
            'qty'=>$Qty,
            'user'=>$user);
    }
}
