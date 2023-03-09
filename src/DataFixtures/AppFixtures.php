<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Role;
use App\Entity\Ticket;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
   private $encoder;

   public function __construct(UserPasswordHasherInterface $encoder){
      $this->encoder = $encoder;
   }
   private function str_rand(int $length = 64)
    {
        $length = ($length < 4) ? 4 : $length;
        return bin2hex(random_bytes(($length - ($length % 2)) / 2));
    }
  
    public function load(ObjectManager $manager): void
    {
      $superadminRole = new Role;
  
      $superadminRole->setTitle('ROLE_SUPERADMIN');
      $manager->persist($superadminRole);
         
            $superadmin = new Admin();
            $hash = $this->encoder->hashPassword($superadmin,"password");
            $superadmin->setFirstName("Eric")
            ->setLastName("BOURDON")
            ->setEmail("eric@thetiptop.com")
            ->setPassword($hash)
            ->setToken($this->str_rand(32))
            ->setCreatedAt(new \DateTimeImmutable())
            ->addRole($superadminRole)
            ->setIsLogin(true);
           
            
            $manager->persist($superadmin);
   
      
       // to do update Hadjer
        //generate a unique 10 number

        function random_1($nub) {
            $string = "";
            $chaine = "0123456789";
            srand((double)microtime()*1000000);
            for($i=0; $i<$nub; $i++) {
            $string .= $chaine[rand()%strlen($chaine)];
            }
            return $string;
        }
         // for($i=0; $i < 900000; $i++){
         //    $ticket = new Ticket();
         //    $ticket->setNumberTicket(random_1(10))
         //           ->setTitle("un infuseur à thé")
         //           ->setStatus(1);
         //    $manager->persist($ticket);
            
         // }
         // for($i=0; $i < 300000; $i++){
         //    $ticket = new Ticket();
         //    $ticket->setNumberTicket(random_1(10))
         //           ->setTitle("une boite de 100g d’un thé détox ou d’infusion")
         //           ->setStatus(1);
         //    $manager->persist($ticket);
            
         // }
         // for($i=0; $i < 150000; $i++){
         //    $ticket = new Ticket();
         //    $ticket->setNumberTicket(random_1(10))
         //           ->setTitle("une boite de 100g d’un thé signature")
         //           ->setStatus(1);
         //    $manager->persist($ticket);
            
         // }
         // for($i=0; $i < 90000; $i++){
         //    $ticket = new Ticket();
         //    $ticket->setNumberTicket(random_1(10))
         //           ->setTitle("un coffret découverte d’une valeur de 69€")
         //           ->setStatus(1);
         //    $manager->persist($ticket);
            
         // }
         // for($i=0; $i < 60000; $i++){
         //    $ticket = new Ticket();
         //    $ticket->setNumberTicket(random_1(10))
         //           ->setTitle("un coffret découverte d’une valeur de 69€")
         //           ->setStatus(1);
         //    $manager->persist($ticket);
            
         // }
         for($i=0; $i < 20; $i++){
            if($i== 1){
               for($i=1; $i < 10; $i++){
                  $ticket = new Ticket();
                  $ticket->setNumberTicket(random_1(10))
                   ->setTitle("un infuseur à thé")
                   ->setStatus("active")
                  ;
                  $manager->persist($ticket);
               }
            }
           
          
           
            
            
         }
         

         
         $manager->flush();
         

         

        
    }
}
