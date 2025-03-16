<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use League\Bundle\OAuth2ServerBundle\Model\Client as BaseClient;

#[ORM\Entity]
#[ORM\Table(name: 'oauth2_client')]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "discr", type: "string")]
#[ORM\DiscriminatorMap(["base" => "League\Bundle\OAuth2ServerBundle\Model\Client", "custom" => "App\Entity\Client"])]
class Client extends BaseClient
{
    
}