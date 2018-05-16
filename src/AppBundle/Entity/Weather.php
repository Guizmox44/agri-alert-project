<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Weather
 *
 * @ORM\Table(name="weather")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WeatherRepository")
 */
class Weather
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(name="image", type="string")
     */
    private $image;


    /**
     * @ORM\Column(name="temperature", type="decimal", precision=2)
     */
    private $temperature;


    /**
     * @ORM\Column(name="moisture", type="decimal", precision=3)
     */
    private $moisture;

    /**
     * @ORM\Column(name="wind_speed", type="integer")
     */
    private $windSpeed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="weather")
     */
    private $users;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \Datetime();
    }

    public function __toString()
    {
        return $this->temperature;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Weather
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Weather
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Weather
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Weather
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set temperature
     *
     * @param string $temperature
     *
     * @return Weather
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;

        return $this;
    }

    /**
     * Get temperature
     *
     * @return string
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * Set moisture
     *
     * @param string $moisture
     *
     * @return Weather
     */
    public function setMoisture($moisture)
    {
        $this->moisture = $moisture;

        return $this;
    }

    /**
     * Get moisture
     *
     * @return string
     */
    public function getMoisture()
    {
        return $this->moisture;
    }

    /**
     * Set windSpeed
     *
     * @param integer $windSpeed
     *
     * @return Weather
     */
    public function setWindSpeed($windSpeed)
    {
        $this->windSpeed = $windSpeed;

        return $this;
    }

    /**
     * Get windSpeed
     *
     * @return integer
     */
    public function getWindSpeed()
    {
        return $this->windSpeed;
    }
}
