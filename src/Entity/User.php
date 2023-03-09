<?php

namespace App\Entity;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Serializable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"type", type:"string")]
#[ORM\DiscriminatorMap(["user" => User::class , "customer" =>Customer::class, 'admin'=>Admin::class])]

#[ApiResource()]
#[ApiFilter(SearchFilter::class, properties : ["email"])]

 Abstract class User implements UserInterface , Serializable, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups( ["get:Customer" , "read:Customer"])]

    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:Customer", "write:Customer"])]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:Customer", "write:Customer"])]
    private $lastName;

    #[ORM\Column(type: 'string', length: 255 , unique: true )]
    #[Groups(["read:Customer", "write:Customer"])]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:Customer", "write:Customer"])]
    private $password;

    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: 'user')]
    #[Groups(["read:Customer"])]
    private $roles;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([ "read:Customer"])]
    private $token;

    private function str_rand(int $length = 64)
    {
        $length = ($length < 4) ? 4 : $length;
        return bin2hex(random_bytes(($length - ($length % 2)) / 2));
    }
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->token = $this->str_rand(32);
    }
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->firstName,
            $this->lastName,
            $this->createdAt,
            $this->token,
        ));
    }
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            $this->firstName,
            $this->lastName,
            $this->createdAt,
            $this->token,
            
        ) = unserialize($serialized);
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    
    public function getUserIdentifier(): string
    {
        return $this->email;
    }
    public function getRoles():array
    {
        $roles = $this->roles->map(function ($role) {
            return $role->getTitle();
        })->toArray();
        $roles[] = 'ROLE_USER';
        return $roles;
    }
    
    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addUser($this);
          
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
       if($this->roles->removeElement($role)){
        $this->roles[] = $role;
        $role->addUser($this);
       }
      
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt( $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }
}
