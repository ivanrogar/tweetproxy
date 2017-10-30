<?php

namespace TweetProxyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SearchController
 * @package TweetProxyBundle\Controller
 *
 * @Security("is_granted('ROLE_USER')")
 */
class SearchController extends Controller
{

    /**
     * @Route("/search/", name="search_page")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction()
    {
        $tweetProxy = $this->get('tp.tweetProxy');
        $following = $tweetProxy->getUser()->getFollowing();

        return $this->render('TweetProxyBundle:Default:search.html.twig', [ 'following' => $following, 'meta' => ['title' => 'Search'] ]);
    }

    /**
     * @param Request $request
     * @Route("/search/do", name="search_page_do")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doSearchAction(Request $request)
    {
        $followingId = trim($request->get('following'));
        $userId = $this->get('tp.tweetProxy')->getUser()->getId();
        $searchQuery = trim($request->get('searchQuery'));

        $em    = $this->get('doctrine')->getManager();
        $query = $em->getRepository('TweetProxyBundle:Tweets')->searchTweets($userId, $followingId, $searchQuery);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('TweetProxyBundle:Default/search:list.html.twig', ['pagination' => $pagination]);
    }
}
