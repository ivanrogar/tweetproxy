<?php

namespace TweetProxyBundle\Model;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TweetProxyBundle\Entity\Following;
use TweetProxyBundle\Entity\Tweets;
use TweetProxyBundle\Entity\User;

/**
 * Class TweetProxy
 * @package TweetProxyBundle\Model
 */
class TweetProxy
{
    private $client;
    private $container;
    private $em;
    private $simpleLogger;

    /**
     * @param ContainerInterface $containerInterface
     */
    public function __construct(ContainerInterface $containerInterface)
    {
        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'consumer_key'    => $containerInterface->getParameter('tweetproxy.consumer_key'),
            'consumer_secret' => $containerInterface->getParameter('tweetproxy.consumer_secret'),
            'token'           => $containerInterface->getParameter('tweetproxy.token'),
            'token_secret'    => $containerInterface->getParameter('tweetproxy.token_secret'),
        ]);
        $stack->push($middleware);

        $this->client = new Client([
            'base_uri' => 'https://api.twitter.com/1.1/',
            'handler' => $stack,
            'auth' => 'oauth'
        ]);

        $this->container = $containerInterface;
        $this->em = $containerInterface->get('doctrine')->getManager();
        $this->simpleLogger = new SimpleLogger();
    }

    /**
     * @param string $screenName
     * @return Following|null
     */
    public function getRecentTweets($screenName)
    {
        try {
            $result = $this->client->get('statuses/user_timeline.json?screen_name=' . $screenName . '&tweet_mode=extended&count=' . (int) $this->container->getParameter('tweetproxy.fetch_count'));

            $tweetsArray = json_decode($result->getBody()->getContents(), true);

            if (count($tweetsArray)) {
                $userImage = str_replace('_normal', '_400x400', $tweetsArray[0]['user']['profile_image_url']);
                $banner = $tweetsArray[0]['user']['profile_banner_url'];

                if (!$this->checkIfBannerAvailable($banner)) {
                    $banner = null;
                }

                // postojeći user?
                $following = $this->em->getRepository('TweetProxyBundle:Following')->findOneBy(['screenName' => $screenName]);
                // novi user
                if (!$following) {
                    $following = new Following();
                    $following
                        ->setScreenName($screenName)
                        ->setUsername($tweetsArray[0]['user']['name'])
                        ->setUserId($tweetsArray[0]['user']['id_str'])
                        ->setUserImage($userImage)
                        ->setUserBanner($banner)
                        ->setUserInfo($tweetsArray[0]['user']['description'])
                        ->setUserLocation($tweetsArray[0]['user']['location']);

                    $this->em->persist($following);
                }
                // update usera?
                else {
                    if ($following->getUserImage() !== $userImage) {
                        $following->setUserImage($userImage);
                    }
                    if ($following->getUserBanner() !== $banner) {
                        $following->setUserBanner($banner);
                    }
                    if ($following->getUserInfo() !== $tweetsArray[0]['user']['description']) {
                        $following->setUserInfo($tweetsArray[0]['user']['description']);
                    }
                    if ($following->getUserLocation() !== $tweetsArray[0]['user']['location']) {
                        $following->setUserLocation($tweetsArray[0]['user']['location']);
                    }
                }

                // update logiranog usera
                if (!$this->isUserFollowing($following)) {
                    $user = $this->getUser();

                    $user->addFollowing($following);
                    $following->addFollower($user);

                    $this->em->persist($user);
                    $this->em->persist($following);
                }

                foreach ($tweetsArray as $tweet) {

                    // update postojećeg tweeta?
                    $tweetsObject = $this->em->getRepository('TweetProxyBundle:Tweets')->findOneBy(['tweetId' => $tweet['id_str']]);
                    // novi tweet
                    if (!$tweetsObject) {
                        $tweetsObject = new Tweets();
                    }

                    $this->convertLinks($tweet);
                    $text = $tweet['full_text'];
                    $this->convertTags($text);

                    $tweetsObject
                        ->setTweetDate(new \DateTime($tweet['created_at']))
                        ->setTweetId($tweet['id_str'])
                        ->setTweetSource($tweet)
                        ->setTweetText($text)
                        ->setUser($following);

                    $this->em->persist($tweetsObject);
                }

                $this->em->flush();
                return $following;
            }
        } catch (\Exception $e) {
            $this->simpleLogger->writeLn($e->getMessage(), 'tweetProxy_exception');
        }

        return null;
    }

    /**
     * @param Following $following
     * @return bool
     */
    public function isUserFollowing($following)
    {
        $user = $this->getUser();

        if ($user->hasFollowing($following)) {
            return true;
        }

        return false;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->container->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * @param string $banner
     * @return bool
     */
    private function checkIfBannerAvailable($banner)
    {
        $request = new Request('GET', $banner);
        $client = new Client();

        try {
            $response = $client->send($request);
            if ($response->getStatusCode() == 200) {
                return true;
            }
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * @param array $tweet
     */
    private function convertLinks(array &$tweet)
    {
        if (isset($tweet['entities']) && isset($tweet['entities']['urls'])) {
            foreach ($tweet['entities']['urls'] as $url) {
                $tweet['full_text'] = str_replace($url['url'], "<a href='{$url['url']}' target='_blank'>{$url['url']}</a>", $tweet['full_text']);
            }
        }
    }

    /**
     * @param $text
     */
    private function convertTags(&$text)
    {
        $regex = '~(@\w+)~';
        if (preg_match_all($regex, $text, $matches, PREG_PATTERN_ORDER)) {
            foreach ($matches[1] as $word) {
                $screenName = str_replace('@', '', $word);
                $text = str_replace($word, "<a href='https://www.twitter.com/" . $screenName . "' target='_blank'>" . $word . "</a>", $text);
            }
        }
    }
}
