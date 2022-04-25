<?php

namespace App\Repository;

use App\Entity\Page;
use DateInterval;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function findFrequentUriRequested(string $orderBy = 'ASC', int $limit): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('p.uri, COUNT(p.uri) AS request')
            ->groupBy('p.uri')
            ->orderBy('request', $orderBy)
            ->setMaxResults($limit);

        return $queryBuilder->getQuery()->execute();
    }

    public function findHttpStatusCodePerPercentage(): array
    {
        // SELECT COUNT(p.http_status_code) FROM page
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('count(p.httpStatusCode)');
        $count = $queryBuilder->getQuery()->execute()[0][1];

        $queryBuilder = $this->createQueryBuilder('p')
            ->select('p.httpStatusCode, COUNT(p.httpStatusCode) * 100 / :count AS percentage')
            ->groupBy('p.httpStatusCode')
            ->setParameter('count', $count);

        return $queryBuilder->getQuery()->execute();
    }

    public function findAveragePagesPerSession()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            select avg(count_id) as average from (
            select visitor_id, count(id) as count_id from page group by visitor_id
            ) a
        ';

        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
        return $stmt->executeStatement();
        //return $stmt->fetchAll()[0]['average']; DEPRECATED !
    }
    public function findAverageTimePerSession()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            select avg(average) as average from (select visitor_id, avg(time_spent) as average from page 
            where time_spent is not null
            group by visitor_id
            ) a
        ';

        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
        return $stmt->executeStatement();
        //return $stmt->fetchAll()[0]['average']; DEPRECATED !
    }

    public function countAllPages()
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('count(p.id)');

        return $queryBuilder->getQuery()->execute()[0][1];
    }

    public function countAllPagesSince12Months(): array
    {
        $lastYear = (new DateTime())->sub(new DateInterval('P1Y'))->format('Y-m-01 00:00:00');

        $lastTwelveMonths = [];
        for ($i = 0; $i < 13; $i++) {
            array_push($lastTwelveMonths, (new DateTime($lastYear))->add(new DateInterval('P' . $i . 'M'))->format('Y-m-d 00:00:00'));
        }

        $results = [];
        for ($k = 0; $k < sizeof($lastTwelveMonths) - 1; $k++) {
            $qb = $this->createQueryBuilder('p')
                ->select('count(p.id) as numberOfPages')
                ->where("p.datetime between '{$lastTwelveMonths[$k]}' AND '{$lastTwelveMonths[$k + 1]}'");
            $results[$k] = $qb->getQuery()->execute()[0];
            $results[$k]['date'] = $lastTwelveMonths[$k];
        }

        return $results;
    }

    public function countAllPagesSince30Days(): array
    {
        $last30Days = [];
        for ($i = 0; $i < 31; $i++) {
            array_unshift($last30Days, (new DateTime())->sub(new DateInterval('P' . $i . 'D'))->format('Y-m-d 00:00:00'));
        }

        $results = [];
        for ($k = 0; $k < sizeof($last30Days) - 1; $k++) {
            $qb = $this->createQueryBuilder('p')
                ->select('count(p.id) as numberOfPages')
                ->where("p.datetime between '{$last30Days[$k]}' AND '{$last30Days[$k + 1]}'");
            $results[$k] = $qb->getQuery()->execute()[0];
            $results[$k]['date'] = $last30Days[$k];
        }

        return $results;
    }

    public function countAllPagesSince24Hours(): array
    {
        $last24Hours = [];
        for ($h = 0; $h < 25; $h++) {
            array_unshift($last24Hours, (new DateTime())->sub(new DateInterval('PT' . $h . 'H'))->format('Y-m-d H:i:s'));
        }

        $results = [];
        for ($k = 0; $k < sizeof($last24Hours) - 1; $k++) {
            $qb = $this->createQueryBuilder('p')
                ->select('count(p.id) as numberOfPages')
                ->where("p.datetime between '{$last24Hours[$k]}' AND '{$last24Hours[$k + 1]}'");
            $results[$k] = $qb->getQuery()->execute()[0];
            $results[$k]['date'] = $last24Hours[$k];
        }

        return $results;
    }

    // /**
    //  * @return Page[] Returns an array of Page objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Page
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
