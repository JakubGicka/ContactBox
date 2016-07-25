<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contacts_Groups
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Contacts_Groups
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
     * @ORM\ManyToOne(targetEntity="Contact", inversedBy="groups")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $contact;
    
    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="contacts")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

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
     * @return Contacts_Groups
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
     * Set group
     *
     * @param string $group
     * @return Contacts_Groups
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return string 
     */
    public function getGroup()
    {
        return $this->group;
    }
}
