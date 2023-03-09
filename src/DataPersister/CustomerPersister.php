<?php


namespace App\DataPersister;

use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Customer;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerPersister implements DataPersisterInterface
{

    private $entityManager;
    private $userPasswordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function supports($data): bool
    {
        return $data instanceof Customer;
    }

    /**
     * @param Customer $data
     */
    public function persist($data)
    {
        if ($data->getPassword()) {
            $data->setPassword($this->userPasswordEncoder->hashPassword(
                    $data,
                    $data->getPassword()
                )
            );
            //$data->eraseCredentials();
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }


    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}