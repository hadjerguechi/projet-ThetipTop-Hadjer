<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin extends User
{
   
    #[ORM\Column(type: 'boolean')]
    private $isLogin;

   

    public function isIsLogin()
    {
        return $this->isLogin;
    }

    public function setIsLogin( $isLogin): self
    {
        $this->isLogin = $isLogin;

        return $this;
    }
}
