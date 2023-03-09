<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ApiResource(
    itemOperations: [
        "get",
        "put"
    ],
    denormalizationContext: ['groups' => ['write:Customer']],
    
    normalizationContext: ['groups' => ['read:Customer']]
    
)]
#[ApiFilter(SearchFilter::class, properties : ["email","token"])]

class Customer extends User
{
   

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["read:Customer", "write:Customer"])]
    private $phone;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["read:Customer", "write:Customer"])]
    private $addresse;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Ticket::class)]
    #[Groups(["read:Customer", "write:Customer"])]
    private $tickets;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $socialMedia;

    public function __construct()
    {
        parent::__construct();
        $this->tickets = new ArrayCollection();
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddresse(): ?string
    {
        return $this->addresse;
    }

    public function setAddresse(string $addresse): self
    {
        $this->addresse = $addresse;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setCustomer($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getCustomer() === $this) {
                $ticket->setCustomer(null);
            }
        }

        return $this;
    }

    public function getSocialMedia(): ?string
    {
        return $this->socialMedia;
    }

    public function setSocialMedia(?string $socialMedia): self
    {
        $this->socialMedia = $socialMedia;

        return $this;
    }

    
}
