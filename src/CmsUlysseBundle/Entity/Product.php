<?php

namespace CmsUlysseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @var bool
     *
     * @ORM\Column(name="valid", type="boolean")
     */
    private $valid;

    /**
     * @Assert\File(
     *     maxSize = "1024k",
     *
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/png", "image/gif"},
     *     mimeTypesMessage = "Choisissez un fichier image valide"
     * )
     */
    private $file;

    /**
     * @var string
     */
    private $tmpImage;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->usersProducts = new ArrayCollection();
        $this->specifications = new ArrayCollection();
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


    /**
     * Set picture
     *
     * @param string $picture
     * @return Product
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        if (isset($this->picture)) {
            $this->tmpImage = $this->picture;
            $this->picture = null;
        } else {
            $this->picture = 'création';
        }

        return $this;
    }


    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $image = sha1(uniqid('img_'));
            $this->setPicture($image . '.' . $this->getFile()->guessExtension());
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $this->getFile()->move($this->getUploadRootDir(), $this->getPicture());

        if (isset($this->tmpImage)) {
            unlink($this->getUploadDir().'/'.$this->tmpImage);
            $this->tmpImage = null;
        }
    }


    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (($file = $this->getAbsolutePath())) {
            unlink($file);
        }
    }

    public function getAbsolutePath()
    {
        if (null === $this->getPicture()) {
            return;
        }
        return $this->getUploadRootDir() . '/' . $this->getPicture();
    }

    public function getWebPath()
    {
        if (null === $this->getPicture()) {
            return;
        }
        return $this->getUploadDir() . '/' . $this->getPicture();
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }


    protected function getUploadDir()
    {
        return 'upload/pictures/products';
    }

    public function getMinPrice()
    {
        $minPrice = 0;

        foreach($this->usersProducts as $userProduct){
            if($minPrice === 0 || $userProduct->getPrice()>$minPrice){
                $minPrice = $userProduct->getPrice();
                $id = $userProduct->getId();
            }
        }

        return array('price'=>$minPrice,'id'=>$id);
    }
}
