<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class, readOnly: true)]
class Location
{
    #[ORM\Id, ORM\Column]
    public int $id;

    #[ORM\Column(type: 'json')]
    public array $coordinates;
}
