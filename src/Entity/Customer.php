<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User.
 *
 * @ORM\Table(name="customers")
 * @ORM\Entity
 */
class Customer implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=45, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="business_name", type="string", length=255)
     */
    private $businessName;

    /**
     * @var string
     *
     * @ORM\Column(name="vat_number", type="string", length=13, unique=true)
     * @Assert\Length(
     *     min=13,
     *     max=13,
     *     minMessage = "V.A.T. Number must be at least {{ limit }} characters long",
     *     maxMessage = "V.A.T. Number cannot be longer than {{ limit }} characters"
     * )
     */
    private $vatNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="company_register", type="string", length=14, unique=true)
     */
    private $companyRegister;

    /**
     * One Customer has Many Addresses.
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AddressCustomer", mappedBy="customer", cascade={"persist"})
     */
    private $addresses;

    /**
     * @var
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json")
     */
    private $roles = [];

    /**
     * @var bool
     *
     * @ORM\Column(name="verified", type="boolean", nullable=true)
     */
    private $verified = false;

    /**
     * @var string
     *
     * @ORM\Column(name="verification_token", type="string", length=255, nullable=true)
     */
    private $verificationToken;

    /**
     * @var bool
     *
     * @ORM\Column(name="alert", type="boolean", nullable=true)
     */
    private $alert = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive = true;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->verificationToken = hash('sha512', uniqid());
        $this->addresses = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Get businessName.
     *
     * @return string
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * Set businessName.
     *
     * @param string $businessName
     *
     * @return Customer
     */
    public function setBusinessName($businessName)
    {
        $this->businessName = $businessName;

        return $this;
    }

    /**
     * Get vatNumber.
     *
     * @return string
     */
    public function getVatNumber()
    {
        return $this->vatNumber;
    }

    /**
     * Set vatNumber.
     *
     * @param string $vatNumber
     *
     * @return Customer
     */
    public function setVatNumber($vatNumber)
    {
        $this->vatNumber = $vatNumber;

        return $this;
    }

    /**
     * Get companyRegister.
     *
     * @return string
     */
    public function getCompanyRegister()
    {
        return $this->companyRegister;
    }

    /**
     * Set companyRegister.
     *
     * @param string $companyRegister
     *
     * @return Customer
     */
    public function setCompanyRegister($companyRegister)
    {
        $this->companyRegister = $companyRegister;

        return $this;
    }

    /**
     * Add address.
     *
     * @param AddressCustomer $address
     *
     * @return Customer
     */
    public function addAddress(AddressCustomer $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setCustomer($this);
        }

        return $this;
    }

    /**
     * Remove address.
     *
     * @param AddressCustomer $address
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAddress(AddressCustomer $address)
    {
        return $this->addresses->removeElement($address);
    }

    /**
     * Get addresses.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles(): array
    {
        $userRoles = $this->roles;
        // guarantees that a user always has at least one role for security
        if (empty($userRoles)) {
            $userRoles[] = 'ROLE_USER';
        }

        return array_unique($userRoles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * @param mixed $verified
     */
    public function setVerified($verified): void
    {
        $this->verified = $verified;
    }

    /**
     * @return mixed
     */
    public function getVerificationToken()
    {
        return $this->verificationToken;
    }

    /**
     * @param mixed $verificationToken
     */
    public function setVerificationToken($verificationToken): void
    {
        $this->verificationToken = $verificationToken;
    }

    /**
     * @return mixed
     */
    public function getAlert()
    {
        return $this->alert;
    }

    /**
     * @param mixed $alert
     */
    public function setAlert($alert): void
    {
        $this->alert = $alert;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one
        return null;
    }

    /**
     * String representation of object.
     *
     * @see   http://php.net/manual/en/serializable.serialize.php
     *
     * @return string the string representation of the object or null
     *
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * Constructs the object.
     *
     * @see   http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
}
