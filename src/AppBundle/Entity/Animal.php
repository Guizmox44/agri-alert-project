<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * animal
 *
 * @ORM\Table(name="animal")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnimalRepository")
 */
class Animal
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
     *
     * @ORM\Column(name="ear_tag", type="string", length=255)
     */
    private $earTag;

    /**
     * @var \Date
     *
     * @ORM\Column(name="arrived_at", type="date")
     */
    private $arrivedAt;

    /**
     * @var \Date
     *
     * @ORM\Column(name="birth_date", type="date")
     */
    private $birthDate;

    /**
     * @var \Date
     *
     * @ORM\Column(name="slaughter_at", type="date", nullable=true)
     */
    private $slaughterAt;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="User", inversedBy="animals")
     */
    private $user;


    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="Species", inversedBy="animals")
     */
    private $species;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="Breed", inversedBy="animals")
     */
    private $breed;

    

    public function __construct()
    {
        $this->arrivedAt = new \DateTime();
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
     * Set earTag
     *
     * @param string $earTag
     *
     * @return Animal
     */
    public function setEarTag($earTag)
    {
        $this->earTag = $earTag;

        return $this;
    }

    /**
     * Get earTag
     *
     * @return string
     */
    public function getEarTag()
    {
        return $this->earTag;
    }

    /**
     * Set arrivedAt
     *
     * @param \DateTime $arrivedAt
     *
     * @return Animal
     */
    public function setArrivedAt($arrivedAt)
    {
        $this->arrivedAt = $arrivedAt;

        return $this;
    }

    /**
     * Get arrivedAt
     *
     * @return \DateTime
     */
    public function getArrivedAt()
    {
        return $this->arrivedAt;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Animal
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set slaughterAt
     *
     * @param \DateTime $slaughterAt
     *
     * @return Animal
     */
    public function setSlaughterAt($slaughterAt)
    {
        $this->slaughterAt = $slaughterAt;

        return $this;
    }

    /**
     * Get slaughterAt
     *
     * @return \DateTime
     */
    public function getSlaughterAt()
    {
        return $this->slaughterAt;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Animal
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set species
     *
     * @param \AppBundle\Entity\Species $species
     *
     * @return Animal
     */
    public function setSpecies(\AppBundle\Entity\Species $species = null)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species
     *
     * @return \AppBundle\Entity\Species
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set breed
     *
     * @param \AppBundle\Entity\Breed $breed
     *
     * @return Animal
     */
    public function setBreed(\AppBundle\Entity\Breed $breed = null)
    {
        $this->breed = $breed;

        return $this;
    }

    /**
     * Get breed
     *
     * @return \AppBundle\Entity\Breed
     */
    public function getBreed()
    {
        return $this->breed;
    }
}
