<?php
// src/AppBundle/Utils/JwtToken.php

namespace AppBundle\Utils;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JwtToken
{
    protected $token;
    protected $secret;
    protected $container;

    public function __construct($username, $secret)
    {
        $signer = new Sha256();

        $this->token = (new Builder())->setIssuer('http://example.com')// Configures the issuer (iss claim)
        ->setAudience('http://example.org')// Configures the audience (aud claim)
        ->setId($username, true)// Configures the id (jti claim), replicating as a header item
        ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
        ->setNotBefore(time())// Configures the time that the token can be used (nbf claim)
        ->setExpiration(time() + 3600)// Configures the expiration time of the token (exp claim)
        ->set('uid', 1)// Configures a new claim, called "uid"
        ->sign($signer, $secret)// creates a signature using "testing" as key
        ->getToken(); // Retrieves the generated token
    }

    public function getString()
    {
        return $this->token->__toString();
    }
}