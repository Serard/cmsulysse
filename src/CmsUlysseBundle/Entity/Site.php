<?php

namespace CmsUlysseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Site
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="CmsUlysseBundle\Entity\SiteRepository")
 */
class Site
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
     * @var string
     *
     * @ORM\Column(name="slogan", type="text", nullable=true)
     */
    private $slogan;

    /**
     * @var string
     *
     * @ORM\Column(name="theme_color", type="string", length=255, nullable=true)
     * @Assert\Regex(
     *     pattern= "/^#(?:(?:[a-f\d]{3}){1,2})$/i",
     *     match=true,
     *     message="Le champ doit être sous forme #FFFFFF"
     * )
     *
     */
    private $themeColor;

    /**
     * @var integer
     *
     * @ORM\Column(name="position_menu", type="boolean")
     */
    private $positionMenu;

    /**
     * @var integer
     *
     * @ORM\Column(name="corps_colonnes", type="integer")
     * @Assert\Regex(
     *     pattern= "/^[1-4]{1}$/",
     *     match=true,
     *     message="Ce doit être compris entre 1 et 4"
     * )
     */
    private $corpsColonnes;

    /**
     * @var string
     *
     * @ORM\Column(name="background", type="string", length=255, nullable=true)
     */
    private $background;

    /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/png", "image/gif"},
     *     mimeTypesMessage = "Choisissez un fichier image valide"
     * )
     */
    private $file;

    /**
     * @var string
     */
    private $tmpImage;

    /**
     * @var bool
     *
     * @ORM\Column(name="slider", type="boolean")
     */
    private $slider;

    /**
     * @var bool
     *
     * @ORM\Column(name="bestProduct", type="boolean")
     */
    private $bestProduct;

    /**
     * @var bool
     *
     * @ORM\Column(name="cmActive", type="boolean")
     */
    private $cmActive;

    /**
     * @var string
     *
     * @ORM\Column(name="communityManagement", type="string")
     */
    private $communityManagement;

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
     * @return Site
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
     * Set slogan
     *
     * @param string $slogan
     * @return Site
     */
    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;

        return $this;
    }

    /**
     * Get slogan
     *
     * @return string 
     */
    public function getSlogan()
    {
        return $this->slogan;
    }

    /**
     * Set themeColor
     *
     * @param string $themeColor
     * @return Site
     */
    public function setThemeColor($themeColor)
    {
        $this->themeColor = $themeColor;

        return $this;
    }

    /**
     * Get themeColor
     *
     * @return string 
     */
    public function getThemeColor()
    {
        return $this->themeColor;
    }

    /**
     * Set positionMenu
     *
     * @param integer $positionMenu
     * @return Site
     */
    public function setPositionMenu($positionMenu)
    {
        $this->positionMenu = $positionMenu;

        return $this;
    }

    /**
     * Get positionMenu
     *
     * @return integer 
     */
    public function getPositionMenu()
    {
        return $this->positionMenu;
    }

    /**
     * Set corpsColonnes
     *
     * @param integer $corpsColonnes
     * @return Site
     */
    public function setCorpsColonnes($corpsColonnes)
    {
        $this->corpsColonnes = $corpsColonnes;

        return $this;
    }

    /**
     * Get corpsColonnes
     *
     * @return integer 
     */
    public function getCorpsColonnes()
    {
        return $this->corpsColonnes;
    }

    /**
     * Set background
     *
     * @param string $background
     * @return Site
     */
    public function setBackground($background)
    {
        $this->background = $background;

        return $this;
    }

    /**
     * Get background
     *
     * @return string
     */
    public function getBackground()
    {
        return $this->background;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        if (isset($this->background)) {
            $this->tmpImage = $this->background;
            $this->background = null;
        } else {
            $this->background = 'création';
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
            $this->setBackground($image . '.' . $this->getFile()->guessExtension());
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

        $this->getFile()->move($this->getUploadRootDir(), $this->getBackground());

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
        if (null === $this->getBackground()) {
            return;
        }
        return $this->getUploadRootDir() . '/' . $this->getBackground();
    }

    public function getWebPath()
    {
        if (null === $this->getBackground()) {
            return;
        }
        return $this->getUploadDir() . '/' . $this->getBackground();
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }


    protected function getUploadDir()
    {
        return 'upload/backgrounds/background';
    }

    /**
     * @return boolean
     */
    public function isSlider()
    {
        return $this->slider;
    }

    /**
     * @param $slider
     * @return $this
     */
    public function setSlider($slider)
    {
        return $this->slider = $slider;
    }

    public function getSlider()
    {
        return $this->slider;
    }

    /**
     * @return mixed
     */
    public function getBestProduct()
    {
        return $this->bestProduct;
    }

    /**
     * @param $bestProduct
     * @return $this
     */
    public function setBestProduct($bestProduct)
    {
        $this->bestProduct = $bestProduct;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCmActive()
    {
        return $this->cmActive;
    }

    /**
     * @param $cmActive
     * @return $this
     */
    public function setCmActive($cmActive)
    {
        $this->cmActive = $cmActive;

        return $this;
    }

    /**
     * @return string
     */
    public function getCommunityManagement()
    {
        return $this->communityManagement;
    }

    /**
     * @param $communityManagement
     * @return $this
     */
    public function setCommunityManagement($communityManagement)
    {
        $this->communityManagement = $communityManagement;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSlideActive()
    {
        return $this->slideActive;
    }

    /**
     * @param boolean $slideActive
     * @return $this
     */
    public function setSlideActive($slideActive)
    {
        $this->slideActive = $slideActive;

        return $this;
    }

}
