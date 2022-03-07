<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function findNearbyCities(): array
    {
        $coordinates = [
            "lat" => 47.105658,
            "lon" => 17.558728,
        ];

        $bb = [
            "south" => 46.8358619417,
            "west" => 17.162345428236,
            "north" => 47.3754540583,
            "east" => 17.955110571764,
        ];

        $query = $this->_em->getConnection()
            ->createQueryBuilder()
            ->select('id, ST_Distance_Sphere(point(JSON_EXTRACT(coordinates, "$.lon"), JSON_EXTRACT(coordinates, "$.lat")), point(:lon, :lat)) as distance')
            ->from('location')
            ->andWhere('JSON_EXTRACT(coordinates, "$.lon") < :east')
            ->andWhere('JSON_EXTRACT(coordinates, "$.lon") > :west')
            ->andWhere('JSON_EXTRACT(coordinates, "$.lat") < :north')
            ->andWhere('JSON_EXTRACT(coordinates, "$.lat") > :south')
            ->orderBy('distance', 'ASC')
            ->setParameter('lon', $coordinates['lon'])
            ->setParameter('lat', $coordinates['lat'])
            ->setParameter('east', $bb['east'])
            ->setParameter('west', $bb['west'])
            ->setParameter('north', $bb['north'])
            ->setParameter('south', $bb['south']);

        return $query->fetchAllAssociative();
    }
}
