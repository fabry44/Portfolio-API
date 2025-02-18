<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use League\Bundle\OAuth2ServerBundle\Model\AccessToken as BaseAccessToken;

#[ORM\Entity]
#[ORM\Table(name: 'oauth2_access_token')]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "discr", type: "string")]
#[ORM\DiscriminatorMap(["base" => "League\Bundle\OAuth2ServerBundle\Model\AccessToken", "custom" => "App\Entity\AccessToken"])]
class AccessToken extends BaseAccessToken
{

}
