<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
    * @return Message[] Returns the info nedded to display the overview of message headers
    */
    public function findHeadersByUser($user)
    {
        return $this->createQueryBuilder('message')
        ->select('DISTINCT u.id','m.body','u.name','u.firstname','u.company')
        ->from('App\Entity\Message', 'm')
        ->where('m.target = :id')
        //->orderBy('m.sendDate', 'ASC')
        ->setParameter('id', $user)
        ->leftJoin('m.sender', 'u')
        ->getQuery()
        ->getResult();
    }

    /**
    * @return Message[] Returns the info of all messages nedded to display a conversation between 2 given users
    */
    public function findConversations($sender,$target)
    {
        return $this->createQueryBuilder('message')
        ->select('DISTINCT m.body','(m.sender) AS sender','(m.target) AS target')
        ->from('App\Entity\Message', 'm')
        ->where('m.target = :target')
        ->orWhere('m.sender = :target')
        ->andWhere('m.sender = :sender')
        ->orWhere('m.target = :sender')
        //->orderBy('m.sendDate', 'ASC')
        ->setParameters(array('target'=>$target,'sender'=>$sender))
        ->getQuery()
        ->getResult();
    }
    



}
