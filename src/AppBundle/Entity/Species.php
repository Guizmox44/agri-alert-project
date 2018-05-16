<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * species
 *
 * @ORM\Table(name="species")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SpeciesRepository")
 */
class Species
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
     * @ORM\OneToMany(targetEntity="Animal", mappedBy="species")
     */
    private $animals;


    /**
     * @ORM\OneToMany(targetEntity="Breed", mappedBy="species")
     */
    private $breeds;




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->animals = new \Doctrine\Common\Collections\ArrayCollection();
        $this->breeds = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set wording
     *
     * @param string $wording
     *
     * @return Species
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
     * Add animal
     *
     * @param \AppBundle\Entity\Animal $animal
     *
     * @return Species
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

    /**
     * Add breed
     *
     * @param \AppBundle\Entity\Breed $breed
     *
     * @return Species
     */
    public function addBreed(\AppBundle\Entity\Breed $breed)
    {
        $this->breeds[] = $breed;

        return $this;
    }

    /**
     * Remove breed
     *
     * @param \AppBundle\Entity\Breed $breed
     */
    public function removeBreed(\AppBundle\Entity\Breed $breed)
    {
        $this->breeds->removeElement($breed);
    }

    /**
     * Get breeds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBreeds()
    {
        return $this->breeds;
    }
}
