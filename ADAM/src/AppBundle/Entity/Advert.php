<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Advert
 *
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AdvertRepository")
 */
class Advert
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;
    /**
     * @var date
     *
     * @ORM\Column(name="createdAt", type="date", length=255)
     * @Assert\NotBlank()
     */
    private $createdAt;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="adverts")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $category;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="evenementDate", type="datetime", length=255, nullable=true)
     */
    private $evenementDate;
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;
    /**
     * @var text
     *
     * @ORM\Column(name="content", type="text", length=16777215)
     * @Assert\NotBlank()
     */
    private $content;

    public function __construct()
    {

        $this->createdAt = new \Datetime();
        $this->updatedAt = new \Datetime();
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
     * Set createdAt
     *
     * @param date $createdAt
     * @return Annonce
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    /**
     * Get createdAt
     *
     * @return date 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * Set title
     *
     * @param string $title
     * @return Annonce
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
     * Set content
     *
     * @param text $content
     * @return Annonce
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    /**
     * Get content
     *
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
    }
    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Advert
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
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     * @return Advert
     */
    public function setAuthor(\AppBundle\Entity\User $author = null)
    {
        $this->author = $author;
        return $this;
    }
    /**
     * Get author
     *
     * @return \AppBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }
    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     * @return Advert
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;
        return $this;
    }
    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set evenementDate
     *
     * @param \DateTime $evenementDate
     * @return Advert
     */
    public function setEvenementDate($evenementDate)
    {
        $this->evenementDate = $evenementDate;

        return $this;
    }

    /**
     * Get evenementDate
     *
     * @return \DateTime 
     */
    public function getEvenementDate()
    {
        return $this->evenementDate;
    }
}
