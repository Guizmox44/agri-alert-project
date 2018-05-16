<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="label", type="string", length=255)
     *
     */
    private $label;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="unit", type="string", length=255)
     */
    private $unit;

    /**
     * @ORM\Column(name="quantity", type="integer")
     * @Assert\NotBlank()
     */
    private $quantity;

    /**
     * @var \Date
     *
     * @ORM\Column(name="expiry_date", type="date", nullable=true)
     *
     */
    private $expiryDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="has_alert", type="boolean")
     */
    private $hasAlert;




    /**
     *  @ORM\Column(name="alert", type="integer", nullable=true)
     */
    private $alert;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlert()
    {
        return $this->alert;
    }

    /**
     * @param mixed $alert
     * @return Product
     */
    public function setAlert($alert)
    {
        $this->alert = $alert;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     * @return Product
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
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
     * Set label
     *
     * @param string $label
     *
     * @return Product
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set unit
     *
     * @param string $unit
     *
     * @return Product
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     *
     * @return Product
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @return mixed
     */
    public function getHasAlert()
    {
        return $this->hasAlert;
    }

    /**
     * @param mixed $hasAlert
     * @return Product
     */
    public function setHasAlert($hasAlert)
    {
        $this->hasAlert = $hasAlert;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Product
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

}
