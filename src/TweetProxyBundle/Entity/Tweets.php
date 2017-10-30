<?php

namespace TweetProxyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Tweets
 * @package TweetProxyBundle\Entity
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="TweetProxyBundle\Repository\TweetsRepository")
 * @ORM\Table(name="tweets", indexes={@ORM\Index(name="tweetTextIdx", columns={"tweet_text"}, flags={"fulltext"})})
 */
class Tweets
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="TweetProxyBundle\Entity\Following", inversedBy="tweets")
     */
    private $user;

    /**
     * @ORM\Column(name="tweet_text", type="string", length=280)
     */
    private $tweetText;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tweetDate;

    /**
     * @ORM\Column(type="string")
     */
    private $tweetId;

    /**
     * @ORM\Column(type="json_array")
     */
    private $tweetSource;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Tweets
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Tweets
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTweetText()
    {
        return $this->tweetText;
    }

    /**
     * @param mixed $tweetText
     * @return Tweets
     */
    public function setTweetText($tweetText)
    {
        $this->tweetText = $tweetText;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTweetDate()
    {
        return $this->tweetDate;
    }

    /**
     * @param \DateTime $tweetDate
     * @return Tweets
     */
    public function setTweetDate($tweetDate)
    {
        $this->tweetDate = $tweetDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTweetId()
    {
        return $this->tweetId;
    }

    /**
     * @param mixed $tweetId
     * @return Tweets
     */
    public function setTweetId($tweetId)
    {
        $this->tweetId = $tweetId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTweetSource()
    {
        return $this->tweetSource;
    }

    /**
     * @param mixed $tweetSource
     * @return Tweets
     */
    public function setTweetSource($tweetSource)
    {
        $this->tweetSource = $tweetSource;
        return $this;
    }

}