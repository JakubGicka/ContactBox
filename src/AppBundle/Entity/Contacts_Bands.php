<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contacts_Bands
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Contacts_Bands
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Contact", inversedBy="bands")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity="Band", inversedBy="contacts")
     * @ORM\JoinColumn(name="band_id", referencedColumnName="id")
     */
    private $band;


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
     * Set contact
     *
     * @param string $contact
     * @return Contacts_Bands
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set band
     *
     * @param string $band
     * @return Contacts_Bands
     */
    public function setBand($band)
    {
        $this->band = $band;

        return $this;
    }

    /**
     * Get band
     *
     * @return string 
     */
    public function getBand()
    {
        return $this->band;
    }
}
