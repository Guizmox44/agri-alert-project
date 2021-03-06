<?php

namespace AppBundle\Repository;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * TaskRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskRepository extends \Doctrine\ORM\EntityRepository
{
    public function findTaskByDay($user,$day)
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.day LIKE :day')
            ->andWhere('t.user = :user')
            ->setParameter( 'day',$day.'%')
            ->setParameter('user', $user)
            ->getQuery();

        $results = $qb->getResult();
        return $results;
    }
}
