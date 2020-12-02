<?php

namespace App\Repository;

use App\Entity\Server;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Server|null find($id, $lockMode = null, $lockVersion = null)
 * @method Server|null findOneBy(array $criteria, array $orderBy = null)
 * @method Server[]    findAll()
 * @method Server[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Server::class);
    }

    /**
     * Retrieves Server information
     * @param int $serverId
     * @return Server|null
     */
    public function findServerByServerId(int $serverId): ?Server
    {
        try {
            return $this->createQueryBuilder('e')
                ->where('e.serverId = :serverId')
                ->setParameter('serverId', $serverId)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }

        return null;
    }
}
