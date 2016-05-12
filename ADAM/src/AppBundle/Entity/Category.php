<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table(name="Category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="wording", type="string", length=255, unique=true)
     */
    private $wording;
    /**
     * @ORM\OneToMany(targetEntity="Advert", mappedBy="category")
     */
    private $adverts;

    public function __construct()
    {
        $this->adverts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Category
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

    public function __toString() {
        return $this->wording;
    }

    /**
     * Add adverts
     *
     * @param \AppBundle\Entity\Advert $adverts
     * @return Category
     */
    public function addAdvert(\AppBundle\Entity\Advert $adverts)
    {
        $this->adverts[] = $adverts;

        return $this;
    }

    /**
     * Remove adverts
     *
     * @param \AppBundle\Entity\Advert $adverts
     */
    public function removeAdvert(\AppBundle\Entity\Advert $adverts)
    {
        $this->adverts->removeElement($adverts);
    }

    /**
     * Get adverts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdverts()
    {
        return $this->adverts;
    }

    /**
    * Set adverts
    *
    * @param \Doctrine\Common\Collections\Collection $adverts
    */
    public function setCategoryAdvert(\Doctrine\Common\Collections\Collection $adverts)
    {
        $this->adverts = $adverts;
    }
}
