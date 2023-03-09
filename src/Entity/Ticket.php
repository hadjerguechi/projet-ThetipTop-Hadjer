<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ApiResource(
    itemOperations: [
        "GET",
        "PUT",
    ],
    denormalizationContext: ['groups' => ['write:Ticket']],
    normalizationContext: ["groups" => ["read:Ticket"]]
)]
#[ApiFilter(SearchFilter::class, properties : ["numberTicket","status"])]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read:Customer", "write:Customer" ,"read:Ticket","write:Ticket"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:Customer", "write:Customer" ,"read:Ticket","write:Ticket"])]
    private $numberTicket;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:Customer", "write:Customer","read:Ticket","write:Ticket"])]
    private $title;

    #[ORM\Column(type: 'string')]
    #[Groups(["read:Customer", "write:Customer","read:Ticket","write:Ticket"])]
    private $status;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'tickets')]
    #[Groups(["read:Ticket","write:Ticket"])]
    private $customer;

   

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberTicket(): ?string
    {
        return $this->numberTicket;
    }

    public function setNumberTicket(string $numberTicket): self
    {
        $this->numberTicket = $numberTicket;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus( $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    

    
}
