<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Mission
 *
 * @ORM\Entity
 * @ORM\Table(name="mission")
 */
class Mission
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
     * @var text
     *
     * @ORM\Column(name="content", type="text", length=16777215)
     * @Assert\NotBlank()
     */
    private $content;
    /**
     * @var text
     *
     * @ORM\Column(name="businessName", type="text")
     * @Assert\NotBlank()
     */
    private $businessName;
    /**
     * @var text
     *
     * @ORM\Column(name="name", type="text")
     * @Assert\NotBlank()
     */
    private $name;
    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @var date
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

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
     * Set content
     *
     * @param text $content
     * @return Mission
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
     * Set businessName
     *
     * @param text $businessName
     * @return Mission
     */
    public function setBusinessName($businessName)
    {
        $this->businessName = $businessName;
        return $this;
    }
    /**
     * Get businessName
     *
     * @return text 
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }
    /**
     * Set name
     *
     * @param text $name
     * @return Mission
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    /**
     * Get name
     *
     * @return text 
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Mission
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
     * Set createdAt
     *
     * @param date $createdAt
     * @return Mission
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Mission
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
}
