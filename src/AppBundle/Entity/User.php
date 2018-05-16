<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\Length(
     *      min = 8,
     *      max = 10,
     *      minMessage = "Votre mot de passe doit avoir au moins  {{ limit }} caractères",
     *      maxMessage = "Votre mot de passe doit avoir moins de {{ limit }} caractères"
     * )
     * @Assert\Regex(
     *      pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)/",
     *      match=true,
     *      message="votre mot de passe doit contenir au moins 1 chiffre, 1 minuscule,1 majuscule et un caractère spécial"
     * )
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "L'email {{ value }}' n'est pas un email valide.",
     *     checkMX = true
     * )
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @ORM\Column(name="siret", type="bigint", options={"unsigned":true})
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 14,
     *      max = 14,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $siret;

    /**
     * @ORM\Column(name="number_livestock", type="integer")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $numberLivestock;

    /**
     * @ORM\Column(name="address", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @ORM\Column(name="zip_code", type="integer")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 5,
     *      max = 5,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $zipCode;

    /**
     * @ORM\Column(name="city", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $city;


    /**
     * @ORM\ManyToOne(targetEntity="Role", cascade={"persist"})
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity="Weather", inversedBy="users", cascade={"persist"})
     */
    private $weather;


    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="user", cascade={"persist"})
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="user", cascade={"persist"})
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="user", cascade={"persist"})
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="Animal", mappedBy="user")
     */
    private $animals;


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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    
    public function getRoles()
    {
        return [$this->getRole()->getCode()];
    }

    public function eraseCredentials()
    {
    }
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }
    /**
     * Set role
     *
     * @param \AppBundle\Entity\Role $role
     *
     * @return User
     */
    public function setRole(\AppBundle\Entity\Role $role = null)
    {
        $this->role = $role;
        return $this;
    }
    /**
     * Get role
     *
     * @return \AppBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }
    public function __toString()
    {
        return $this->username;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set siret
     *
     * @param integer $siret
     *
     * @return User
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return integer
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * Set numberLivestock
     *
     * @param integer $numberLivestock
     *
     * @return User
     */
    public function setNumberLivestock($numberLivestock)
    {
        $this->numberLivestock = $numberLivestock;

        return $this;
    }

    /**
     * Get numberLivestock
     *
     * @return integer
     */
    public function getNumberLivestock()
    {
        return $this->numberLivestock;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zipCode
     *
     * @param integer $zipCode
     *
     * @return User
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return integer
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set weather
     *
     * @param \AppBundle\Entity\Weather $weather
     *
     * @return User
     */
    public function setWeather(\AppBundle\Entity\Weather $weather = null)
    {
        $this->weather = $weather;

        return $this;
    }

    /**
     * Get weather
     *
     * @return \AppBundle\Entity\Weather
     */
    public function getWeather()
    {
        return $this->weather;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->animals = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add animal
     *
     * @param \AppBundle\Entity\Animal $animal
     *
     * @return User
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
     * Add message
     *
     * @param \AppBundle\Entity\Message $message
     *
     * @return User
     */
    public function addMessage(\AppBundle\Entity\Message $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \AppBundle\Entity\Message $message
     */
    public function removeMessage(\AppBundle\Entity\Message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     * @return User
     */
    public function setProducts($products)
    {
        $this->products = $products;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param mixed $tasks
     * @return User
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
        return $this;
    }


    /**
     * Add task
     *
     * @param \AppBundle\Entity\Task $task
     *
     * @return User
     */
    public function addTask(\AppBundle\Entity\Task $task)
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Remove task
     *
     * @param \AppBundle\Entity\Task $task
     */
    public function removeTask(\AppBundle\Entity\Task $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * Add product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return User
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \AppBundle\Entity\Product $product
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Add speciess
     *
     * @param \AppBundle\Entity\Species $speciess
     *
     * @return User
     */
    public function addSpeciess(\AppBundle\Entity\Species $speciess)
    {
        $this->speciess[] = $speciess;

        return $this;
    }

    /**
     * Remove speciess
     *
     * @param \AppBundle\Entity\Species $speciess
     */
    public function removeSpeciess(\AppBundle\Entity\Species $speciess)
    {
        $this->speciess->removeElement($speciess);
    }

    /**
     * Get speciess
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpeciess()
    {
        return $this->speciess;
    }

    /**
     * Add breed
     *
     * @param \AppBundle\Entity\Breed $breed
     *
     * @return User
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
