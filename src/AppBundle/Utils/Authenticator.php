<?php
// src AppBundle/Utils/Authenticator

namespace AppBundle\Utils;

use AppBundle\Entity\Users;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

class Authenticator
{
    protected $authenticated = false;
    protected $token;
    protected $user;

    public function __construct($secret, $cookie_value)
    {
        $signer = new Sha256();
        $this->user = new Users();

        if (isset($_SESSION['user'])) {
            $this->authenticated = true;
            $this->user = unserialize($_SESSION['user']);
        } elseif (isset($_COOKIE[$cookie_value])) {
            $this->token = (new Parser())->parse((string)$_COOKIE[$cookie_value]);
            if ($this->token->verify($signer, $secret)) {
                $data = new ValidationData();
                $data->setIssuer('http://example.com');
                $data->setAudience('http://example.org');
                $data->setCurrentTime(time());
                if ($this->token->validate($data)) {
                    $this->authenticated = true;
                    $this->user = unserialize($this->token->getClaim('user'));
                }
            }
        }
    }
    public function isAuthenticated()
    {
        return $this->authenticated;
    }

    public function getUser()
    {
        return $this->user;
    }
}