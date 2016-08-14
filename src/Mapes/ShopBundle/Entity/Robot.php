<?php

namespace Mapes\ShopBundle\Entity;

/**
 * Robot
 */
class Robot
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $shop_id;

    /**
     * @var integer
     */
    private $pos_x;

    /**
     * @var integer
     */
    private $pos_y;

    /**
     * @var string
     */
    private $heading;

    /**
     * @var string
     */
    private $commands;

    /**
     * @var \Mapes\ShopBundle\Entity\Shop
     */
    private $shop;


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
     * Set shopId
     *
     * @param integer $shopId
     *
     * @return Robot
     */
    public function setShopId($shopId)
    {
        $this->shop_id = $shopId;

        return $this;
    }

    /**
     * Get shopId
     *
     * @return integer
     */
    public function getShopId()
    {
        return $this->shop_id;
    }

    /**
     * Set posX
     *
     * @param integer $posX
     *
     * @return Robot
     */
    public function setPosX($posX)
    {
        $this->pos_x = $posX;

        return $this;
    }

    /**
     * Get posX
     *
     * @return integer
     */
    public function getPosX()
    {
        return $this->pos_x;
    }

    /**
     * Set posY
     *
     * @param integer $posY
     *
     * @return Robot
     */
    public function setPosY($posY)
    {
        $this->pos_y = $posY;

        return $this;
    }

    /**
     * Get posY
     *
     * @return integer
     */
    public function getPosY()
    {
        return $this->pos_y;
    }

    /**
     * Set heading
     *
     * @param string $heading
     *
     * @return Robot
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;

        return $this;
    }

    /**
     * Get heading
     *
     * @return string
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * Set commands
     *
     * @param string $commands
     *
     * @return Robot
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * Get commands
     *
     * @return string
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Set shop
     *
     * @param \Mapes\ShopBundle\Entity\Shop $shop
     *
     * @return Robot
     */
    public function setShop(\Mapes\ShopBundle\Entity\Shop $shop = null)
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Get shop
     *
     * @return \Mapes\ShopBundle\Entity\Shop
     */
    public function getShop()
    {
        return $this->shop;
    }
}

