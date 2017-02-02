<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function getExpiresAt() {
        return $this->expiresAt;
    }

    public function expiresAt() {
        return $this->expiresAt;
    }

    public function isExpiresAt() {
        return $this->isExpiresAt;
    }

    public function hasExpiresAt() {
        return $this->hasExpiresAt;
    }
}