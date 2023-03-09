<?php

namespace App\Controller\Backoffice;

use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/backoffice')]
class CustomerController extends AbstractController
{
    #[Route('/clients', name: 'backoffice_customer')]
    public function index(CustomerRepository $customerRepos): Response
    {
        $customers = $customerRepos->findAll();
        return $this->render('backoffice/customer/customers.html.twig', [
            'controller_name' => 'CustomerController',
            "customers" => $customers
        ]);
    }

    #[Route('/client/{id}', name:'customer_detail')]
    public function customer_detail(){
        return $this->render("",[

        ]);
    }
    #[Route('/modification/client/{id}', name:'edit_customer')]
    public function edit_customer(){
        return $this->render("",[

        ]);
    }
}
