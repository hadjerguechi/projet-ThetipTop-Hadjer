<?php

namespace App\Controller\Backoffice;

use App\Repository\CustomerRepository;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/backoffice')]
class AdminDashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(CustomerRepository $customerRepos, TicketRepository $ticketRepos): Response
    {
        $participated = [];
        $users = $customerRepos->findAll();
        $tickets = $ticketRepos->findAll();
        
        foreach($tickets as $ticket){
           
            if($ticket->getCustomer()!= null){
                $participated [] = $ticket;
            }
        }
        
        return $this->render('backoffice/dashboard.html.twig', [
            'menu' => 'dashboard',
            'user'=> $users,
            'participated'=> $participated

        ]);
    }

    

}
