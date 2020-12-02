<?php

namespace App\Repository;

use App\Entity\ServiceProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;

/**
 * @method ServiceProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceProvider[]    findAll()
 * @method ServiceProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceProvider::class);
    }

    public function findOneByIp(string $ip): ?ServiceProvider
    {
        try {
            return $this->createQueryBuilder('e')
                ->where('e.ipAddress = :ipAddress')
                ->setParameter('ipAddress', u($ip)->trim()->toString())
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }

        return null;
    }
}
