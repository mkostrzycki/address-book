<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Contact
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Address", mappedBy="contact")
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PhoneNumber", mappedBy="contact")
     */
    private $phoneNumbers;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\EmailAddress", mappedBy="contact")
     */
    private $emailAddresses;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ContactGroup", mappedBy="contacts")
     */
    private $contactGroups;

    /**
     * @var string
     *
     * @ORM\Column(name="picture_path", type="string", length=255)
     */
    private $picturePath;

    /**
     * @var UploadedFile
     *
     * @Assert\File(maxSize="1M")
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png" })
     */
    private $pictureFile;

    /**
     * Contact constructor.
     */
    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
        $this->emailAddresses = new ArrayCollection();
        $this->contactGroups = new ArrayCollection();
        // default picture for new contacts
        $this->picturePath = 'noPicture.png';
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
     * Set name
     *
     * @param string $name
     * @return Contact
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return Contact
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Contact
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add addresse
     *
     * @param Address $address
     * @return Contact
     */
    public function addAddress(Address $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param Address $address
     */
    public function removeAddress(Address $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Add phoneNumber
     *
     * @param PhoneNumber $phoneNumber
     * @return Contact
     */
    public function addPhoneNumber(PhoneNumber $phoneNumber)
    {
        $this->phoneNumbers[] = $phoneNumber;

        return $this;
    }

    /**
     * Remove phoneNumber
     *
     * @param PhoneNumber $phoneNumber
     */
    public function removePhoneNumber(PhoneNumber $phoneNumber)
    {
        $this->phoneNumbers->removeElement($phoneNumber);
    }

    /**
     * Get phoneNumbers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    /**
     * Add emailAddress
     *
     * @param EmailAddress $emailAddress
     * @return Contact
     */
    public function addEmailAddress(EmailAddress $emailAddress)
    {
        $this->emailAddresses[] = $emailAddress;

        return $this;
    }

    /**
     * Remove emailAddress
     *
     * @param EmailAddress $emailAddress
     */
    public function removeEmailAddress(EmailAddress $emailAddress)
    {
        $this->emailAddresses->removeElement($emailAddress);
    }

    /**
     * Get emailAddresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmailAddresses()
    {
        return $this->emailAddresses;
    }

    /**
     * Add contactGroup
     *
     * @param ContactGroup $contactGroup
     * @return Contact
     */
    public function addContactGroup(ContactGroup $contactGroup)
    {
        $this->contactGroups[] = $contactGroup;

        return $this;
    }

    /**
     * Remove contactGroup
     *
     * @param ContactGroup $contactGroup
     */
    public function removeContactGroup(ContactGroup $contactGroup)
    {
        $this->contactGroups->removeElement($contactGroup);
    }

    /**
     * Get contactGroups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContactGroups()
    {
        return $this->contactGroups;
    }

    /**
     * Get picture path
     *
     * @return string
     */
    public function getPicturePath()
    {
        return $this->picturePath;
    }

    /**
     * Set picture path
     *
     * @param $picturePath
     * @return $this
     */
    public function setPicturePath($picturePath)
    {
        $this->picturePath = $picturePath;

        return $this;
    }

    /**
     * Get uploaded picture file
     *
     * @return UploadedFile
     */
    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    /**
     * Set uploaded picture file
     *
     * @param UploadedFile $pictureFile
     * @return $this
     */
    public function setPictureFile($pictureFile)
    {
        $this->pictureFile = $pictureFile;

        return $this;
    }

    public function getAbsolutePicturePath()
    {
        return null === $this->picturePath ? null : $this->getUploadRootDir() . '/' . $this->picturePath;
    }

    public function getWebPicturePath()
    {
        return null === $this->picturePath ? null : $this->getUploadDir() . '/' . $this->picturePath;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/pictures';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->pictureFile) {
            // unikalna nazwa
            $this->setPicturePath(uniqid() . '.' . $this->pictureFile->guessExtension());
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->pictureFile) {
            return;
        }

        $this->pictureFile->move($this->getUploadRootDir(), $this->picturePath);

        unset($this->pictureFile);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePicturePath()) {
            unlink($file);
        }
    }
}
