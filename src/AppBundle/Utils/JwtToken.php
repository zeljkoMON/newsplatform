<?php
// src/AppBundle/Utils/JwtToken

namespace AppBundle\Utils;

use AppBundle\Entity\Users;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JwtToken
{
    protected $token;

    public function __construct(Users $user, $secret, $time)
    {
        $signer = new Sha256();

        $this->token = (new Builder())->setIssuer('http://example.com')// Configures the issuer (iss claim)
        ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
        ->setNotBefore(time())// Configures the time that the token can be used (nbf claim)
        ->setExpiration(time() + $time)// Configures the expiration time of the token (exp claim)
        ->set('user', serialize($user))
        ->sign($signer, $secret)// creates a signature using "testing" as key
        ->getToken(); // Retrieves the generated token
    }
    public function getString()
    {
        return $this->token->__toString();
    }
    public function __toString()
    {
        return $this->token->__toString();
    }
}