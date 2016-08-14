<?php

namespace Mapes\ShopBundle\Entity;

/**
 * Shop
 */
class Shop
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $width;

    /**
     * @var integer
     */
    private $height;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $robots;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->robots = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set width
     *
     * @param integer $width
     *
     * @return Shop
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Shop
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Add robot
     *
     * @param \Mapes\ShopBundle\Entity\Robot $robot
     *
     * @return Shop
     */
    public function addRobot(\Mapes\ShopBundle\Entity\Robot $robot)
    {
        $this->robots[] = $robot;

        return $this;
    }

    /**
     * Remove robot
     *
     * @param \Mapes\ShopBundle\Entity\Robot $robot
     */
    public function removeRobot(\Mapes\ShopBundle\Entity\Robot $robot)
    {
        $this->robots->removeElement($robot);
    }

    /**
     * Get robots
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRobots()
    {
        return $this->robots;
    }
}

