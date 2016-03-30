<?php
// src AppBundle/Utils/TokenAuthenticator

namespace AppBundle\Utils;

use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

class TokenAuthenticator
{
    protected $authenticated = false;
    protected $token;
    protected $user;
    protected $admin = 0;

    public function __construct($secret, $cookie_value)
    {
        $signer = new Sha256();

        if (isset($_SESSION['username'])) {
            $this->authenticated = true;
            $this->user = $_SESSION['username'];
            $this->admin = $_SESSION['admin'];
        } elseif (isset($_COOKIE[$cookie_value])) {
            $this->token = (new Parser())->parse((string)$_COOKIE[$cookie_value]);
            if ($this->token->verify($signer, $secret)) {
                $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
                $data->setIssuer('http://example.com');
                $data->setAudience('http://example.org');
                $data->setCurrentTime(time());
                if ($this->token->validate($data)) {
                    $this->authenticated = true;
                    $this->user = $this->token->getClaim('user');
                    $this->admin = $this->token->getClaim('admin');
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

    public function isAdmin()
    {
        return $this->admin;
    }
}