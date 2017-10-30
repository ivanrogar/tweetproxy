<?php

namespace TweetProxyBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use TweetProxyBundle\Entity\Tweets;

/**
 * Class TweetsRepository
 * @package TweetProxyBundle\Repository
 */
class TweetsRepository extends EntityRepository
{

    /**
     * @param $screenName
     * @return Tweets[]|null
     */
    public function getUserTweets ($screenName, $limit = null) {

        $query = $this->createQueryBuilder('t');
        $query
            ->innerJoin('t.user', 'user')
            ->where('user.screenName = :screenName')
            ->setParameter('screenName', $screenName)
            ->addOrderBy('t.tweetDate', 'DESC');

        if ( (int) $limit) {
            $query
                ->setMaxResults($limit);
        }

        return $query
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $userId
     * @param int $followingId
     * @param string|null $searchQuery
     * @return QueryBuilder
     */
    public function searchTweets ($userId, $followingId, $searchQuery) {

        $query = $this->createQueryBuilder('t');

        $query
            ->innerJoin('t.user', 'following')
            ->innerJoin('following.followers', 'followers')
            ->where('followers.id = :userId')
            ->setParameter('userId', (int) $userId);

        if (trim($searchQuery)) {
            $query
                ->andWhere("MATCH (t.tweetText) AGAINST (:searchQuery) != 0")
                ->setParameter('searchQuery', $searchQuery);
        }

        if ( (int) ($followingId)) {
            $query
                ->andWhere('following.id = :followingId')
                ->setParameter('followingId', (int) $followingId);
        }

        return $query;
    }

}