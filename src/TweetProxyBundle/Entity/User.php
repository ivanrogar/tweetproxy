<?php

namespace TweetProxyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Doctrine\ORM\Mapping\Entity()
 * @Doctrine\ORM\Mapping\Table(name="user")
 */
class User implements UserInterface
{
    public function __construct()
    {
        $this->following = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint", options={"unsigned" = true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string
     */
    private $passwordPlain;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity="TweetProxyBundle\Entity\Following")
     */
    private $following;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActivated;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     *
     */
    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
        $this->passwordPlain = null;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPasswordPlain()
    {
        return $this->passwordPlain;
    }

    /**
     * @param mixed $passwordPlain
     * @return User
     */
    public function setPasswordPlain($passwordPlain)
    {
        $this->passwordPlain = $passwordPlain;
        $this->password = null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return Following[]|null
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * @param Following[]|null $following
     * @return User
     */
    public function setFollowing($following)
    {
        $this->following = $following;
        return $this;
    }

    /**
     * @param Following $following
     */
    public function addFollowing($following)
    {
        if (!$this->following->contains($following)) {
            $this->following->add($following);
        }
    }

    /**
     * @param Following $following
     */
    public function removeFollowing($following)
    {
        if ($this->following->contains($following)) {
            $this->following->removeElement($following);
        }
    }

    /**
     * @param Following $following
     * @return bool
     */
    public function hasFollowing($following)
    {
        if ($this->following->contains($following)) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getIsActivated()
    {
        return $this->isActivated;
    }

    /**
     * @param mixed $isActivated
     * @return User
     */
    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;
        return $this;
    }
}
