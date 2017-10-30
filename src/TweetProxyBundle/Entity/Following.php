<?php

namespace TweetProxyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Following
 * @package TweetProxyBundle\Entity
 * @Doctrine\ORM\Mapping\Entity()
 * @Doctrine\ORM\Mapping\Table(name="following")
 */
class Following
{

    public function __construct () {
        $this->followers = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $screenName;

    /**
     * @ORM\Column(type="string")
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     */
    private $userId;

    /**
     * @ORM\Column(type="string")
     */
    private $userInfo;

    /**
     * @ORM\Column(type="string")
     */
    private $userImage;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $userBanner;

    /**
     * @ORM\Column(type="string")
     */
    private $userLocation;

    /**
     * @ORM\OneToMany(targetEntity="TweetProxyBundle\Entity\Tweets", mappedBy="user", cascade={"persist", "remove"})
     */
    private $tweets;

    /**
     * @ORM\ManyToMany(targetEntity="TweetProxyBundle\Entity\User")
     */
    private $followers;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Following
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getScreenName()
    {
        return $this->screenName;
    }

    /**
     * @param mixed $screenName
     * @return Following
     */
    public function setScreenName($screenName)
    {
        $this->screenName = $screenName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return Following
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     * @return Following
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * @param mixed $userInfo
     * @return Following
     */
    public function setUserInfo($userInfo)
    {
        $this->userInfo = $userInfo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserImage()
    {
        return $this->userImage;
    }

    /**
     * @param mixed $userImage
     * @return Following
     */
    public function setUserImage($userImage)
    {
        $this->userImage = $userImage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserBanner()
    {
        return $this->userBanner;
    }

    /**
     * @param mixed $userBanner
     * @return Following
     */
    public function setUserBanner($userBanner)
    {
        $this->userBanner = $userBanner;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserLocation()
    {
        return $this->userLocation;
    }

    /**
     * @param mixed $userLocation
     * @return Following
     */
    public function setUserLocation($userLocation)
    {
        $this->userLocation = $userLocation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTweets()
    {
        return $this->tweets;
    }

    /**
     * @param mixed $tweets
     * @return Following
     */
    public function setTweets($tweets)
    {
        $this->tweets = $tweets;
        return $this;
    }

    /**
     * @return User[]|null
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * @param User[]|null $followers
     * @return Following
     */
    public function setFollowers($followers)
    {
        $this->followers = $followers;
        return $this;
    }

    /**
     * @param User $follower
     */
    public function addFollower($follower) {
        if (!$this->followers->contains($follower)) {
            $this->followers->add($follower);
        }
    }

    /**
     * @param User $follower
     */
    public function removeFollower($follower) {
        if ($this->followers->contains($follower)) {
            $this->followers->removeElement($follower);
        }
    }

    /**
     * @param User $follower
     * @return bool
     */
    public function hasFollower($follower) {
        if ($this->followers->contains($follower)) {
            return true;
        }

        return false;
    }

}