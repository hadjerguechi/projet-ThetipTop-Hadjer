<?php

namespace App\Controller\Backoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


#[Route('/backoffice')]
class AdminAccountController extends AbstractController
{
    private function str_rand(int $length = 64)
    {
        $length = ($length < 4) ? 4 : $length;
        return bin2hex(random_bytes(($length - ($length % 2)) / 2));
    }
    
    #[Route('/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $utils): Response
    {
        $user = $this->getUser();
        if($user != null )
        {
            return $this->redirectToRoute("admin_dashboard");
        }
        else{
            $error = $utils->getLastAuthenticationError();
            $username = $utils->getLastUsername();
            //dd($username);
            return $this->render('backoffice/admin_account/login.html.twig', [
                'error' => $error,
                'username' => $username,
            ]);
        }
       
    }
   

   

    #[Route('/deconnexion', name: "app_logout")]
    public function logout(){

    }
}
