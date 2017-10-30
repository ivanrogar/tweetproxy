<?php

namespace TweetProxyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class IndexController
 * @package TweetProxyBundle\Controller
 *
 * @Security("is_granted('ROLE_USER')")
 */
class IndexController extends Controller
{

    /**
     * @param $screenName
     * @Route("/{screenName}", name="main_index_page", defaults={"screenName" = null})
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($screenName)
    {
        $em = $this->get('doctrine')->getManager();
        $meta['title'] = 'Following';
        $tweetProxy = $this->get('tp.tweetProxy');

        if (trim($screenName)) {
            $following = $tweetProxy->getRecentTweets($screenName);

            // user nije nađen, nema podataka
            if (!$following) {
                $this->addFlash('danger', 'Twitter user was not found. Are your API keys added to config?');
                return new RedirectResponse($this->generateUrl('main_index_page'));
            }

            $tweets = $em->getRepository('TweetProxyBundle:Tweets')->getUserTweets($screenName, $this->getParameter('tweetproxy.display_count'));

            $meta['title'] .= ' - ' . $following->getUsername();
            return $this->render('TweetProxyBundle:Default/user:view.html.twig', ['tweets' => $tweets, 'following' => $following, 'meta' => $meta]);
        } else {
            $following = $tweetProxy->getUser()->getFollowing();
            return $this->render('TweetProxyBundle:Default:index.html.twig', ['following' => $following, 'meta' => $meta]);
        }
    }

    /**
     * @param int $followingId
     * @Route("/unfollow/{followingId}")
     * @return RedirectResponse
     */
    public function unfollowAction($followingId)
    {
        $em = $this->get('doctrine')->getManager();
        $tweetProxy = $this->get('tp.tweetProxy');
        $following = $em->getRepository('TweetProxyBundle:Following')->find((int) $followingId);

        if (!$following) {
            $this->addFlash('warning', 'Twitter user not found.');
        } else {
            if ($tweetProxy->isUserFollowing($following)) {
                $user = $tweetProxy->getUser();
                $user->removeFollowing($following);
                $following->removeFollower($user);

                $em->persist($user);
                $em->persist($following);
                $em->flush();

                $this->addFlash('success', 'Twitter user unfollowed.');
            } else {
                $this->addFlash('warning', "You're not following this user!");
            }
        }

        return new RedirectResponse($this->generateUrl('main_index_page'));
    }
}
