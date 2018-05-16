<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * breed
 *
 * @ORM\Table(name="breed")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BreedRepository")
 */
class Breed
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
     * @ORM\Column(name="wording", type="string", length=255)
     */
    private $wording;


    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="Species", inversedBy="breeds", cascade={"persist"})
     */
    private $species;

    /**
     * @ORM\OneToMany(targetEntity="Animal", mappedBy="breed")
     */
    private $animals;


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
     * Set wording
     *
     * @param string $wording
     *
     * @return Breed
     */
    public function setWording($wording)
    {
        $this->wording = $wording;

        return $this;
    }

    /**
     * Get wording
     *
     * @return string
     */
    public function getWording()
    {
        return $this->wording;
    }

    /**
     * Set species
     *
     * @param \AppBundle\Entity\Species $species
     *
     * @return Breed
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
     * Constructor
     */
    public function __construct()
    {
        $this->animals = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add animal
     *
     * @param \AppBundle\Entity\Animal $animal
     *
     * @return Breed
     */
    public function addAnimal(\AppBundle\Entity\Animal $animal)
    {
        $this->animals[] = $animal;

        return $this;
    }

    /**
     * Remove animal
     *
     * @param \AppBundle\Entity\Animal $animal
     */
    public function removeAnimal(\AppBundle\Entity\Animal $animal)
    {
        $this->animals->removeElement($animal);
    }

    /**
     * Get animals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnimals()
    {
        return $this->animals;
    }
}
