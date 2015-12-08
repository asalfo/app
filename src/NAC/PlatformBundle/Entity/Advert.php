<?php

namespace NAC\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
// N'oubliez pas de rajouter ce « use », il définit le namespace pour les annotations de validation
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="NAC\PlatformBundle\Entity\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="title", message="Une annonce existe déjà avec ce titre.")
 */
class Advert
{
    /**
     * @ORM\OneToMany(targetEntity="NAC\PlatformBundle\Entity\Application", mappedBy="advert")
     */
    private $applications; // Notez le « s », une annonce est liée à plusieurs candidatures

	/**
     * @ORM\ManyToMany(targetEntity="NAC\PlatformBundle\Entity\Category", cascade={"persist"})
     */
    private $categories;
	
	/**
     * @ORM\OneToOne(targetEntity="NAC\PlatformBundle\Entity\Image", cascade={"persist", "remove"})
	 * @Assert\Valid()
	 */
    private $image;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
	 */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     * @Assert\Length(min=10)
	 */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     * @Assert\Length(min=2)
	 */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
	 */
    private $content;
    
	/**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;
 
	/**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

	/**
     * @ORM\Column(name="nb_applications", type="integer")
     */
    private $nbApplications = 0;
  
	public function __construct()
    {
		// Par défaut, la date de l'annonce est la date d'aujourd'hui
		$this->date = new \Datetime();
		$this->categories = new ArrayCollection();
		$this->applications = new ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Advert
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set image
     *
     * @param \NAC\PlatformBundle\Entity\Image $image
     *
     * @return Advert
     */
    public function setImage(\NAC\PlatformBundle\Entity\Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \NAC\PlatformBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add category
     *
     * @param \NAC\PlatformBundle\Entity\Category $category
     *
     * @return Advert
     */
    public function addCategory(\NAC\PlatformBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \NAC\PlatformBundle\Entity\Category $category
     */
    public function removeCategory(\NAC\PlatformBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add application
     *
     * @param \NAC\PlatformBundle\Entity\Application $application
     *
     * @return Advert
     */
    public function addApplication(\NAC\PlatformBundle\Entity\Application $application)
    {
        $this->applications[] = $application;
      
	  // On lie l'annonce à la candidature
         $application->setAdvert($this);
        
		return $this;
    }

    /**
     * Remove application
     *
     * @param \NAC\PlatformBundle\Entity\Application $application
     */
    public function removeApplication(\NAC\PlatformBundle\Entity\Application $application)
    {
        $this->applications->removeElement($application);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApplications()
    {
        return $this->applications;
    }
	
	/**
     * @ORM\PreUpdate
     */
	public function updateDate()
    {
     $this->setUpdatedAt(new \Datetime());
    }
	
    public function increaseApplication()
    {
     $this->nbApplications++;
    }

    public function decreaseApplication()
    {
     $this->nbApplications--;
    }
	
}
