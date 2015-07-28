<?php

namespace CmsUlysseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandUserProduct
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CommandUserProductRepository")
 */
class CommandUserProduct
{
    /**
     * @ORM\ManyToOne(targetEntity="Command", inversedBy="commandUserProducts")
     * @ORM\Id
     * @ORM\JoinColumn(name="command_id", referencedColumnName="id")
     */
    private $command;

    /**
     * @ORM\ManyToOne(targetEntity="UserProduct", inversedBy="commands")
     * @ORM\Id
     * @ORM\JoinColumn(name="user_product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @var integer
     *
     * @ORM\Column(name="qty", type="integer")
     */
    private $qty;

    /**
     * Set qty
     *
     * @param integer $qty
     * @return CommandUserProduct
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
     * Set command
     *
     * @param Command $command
     * @return CommandUserProduct
     */
    public function setCommand(Command $command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command
     *
     * @return Command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set product
     *
     * @param UserProduct $product
     * @return CommandUserProduct
     */
    public function setProduct(UserProduct $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return UserProduct
     */
    public function getProduct()
    {
        return $this->product;
    }

}
