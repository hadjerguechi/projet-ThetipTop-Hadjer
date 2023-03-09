<?php

namespace App\Controller\Front;

use App\EmailNotification\ToUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomepageController extends AbstractController
{
    private $client;
    private $apiUrl;
    private $toUser;

    public function __construct(HttpClientInterface $httpClientInterface, $apiUrl,ToUser $toUser)
    {
        $this->client = $httpClientInterface;
        $this->apiUrl = $apiUrl;
        $this->toUser = $toUser;
        
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        // homepage
        return $this->render('/front/homepage/homepage.html.twig', [
            "menu" => "homepage",
            
        ]);
    }
    #[Route('/inscription', name: 'registration')]
    public function registration(Request  $request , ToUser $toUser){
        
        $data = json_decode($request->getContent(), true);
        
       
        $email = $data['email'];

        $responseUser = $this->client->request(
            'GET',
            $this->apiUrl . "/users?email=".$email
        );
        $user = json_decode($responseUser->getContent(), true)['hydra:member'];
        
        if($user == null){
            $bodyParam = [
                "firstName" => $data["firstName"],
                "lastName" => $data["lastName"],
                "email" => $data["email"],
                "password" => $data["password"],
                "phone" => "1234567",
                "addresse" => $data["address"]

            ];
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/customers',
                [
                    'headers' => [
                        'content-type' => 'application/json'
                    ],
                    'body' => json_encode($bodyParam)
                ]
            );
           $customer = json_decode($response->getContent(), true);

            $toUser->confirmEmail($customer['email'], $customer['firstName'], $customer['token']);
            return new JsonResponse([
                'status' => 'success',
                'message' => 'Votre inscription a bien été prise en compte'
            ]);

        }else{
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Cet email est déjà associé à un compte'
            ]);
        }
         
    }
    #[Route( '/login_check' , name: 'login')]
    public function login(Request $request){
        
        $session = $request->getSession();
        $bodyParam = [
            'email' => $request->request->get('_username'),
            'password' => $request->request->get('_password')
        ];
      
        $response = $this->client->request(
            'PUT',
            $this->apiUrl. "/login_check",
            [
                'headers' => [
                    'content-type' => 'application/json'
                ],
                'body' => json_encode($bodyParam)
            ]
        );
       

        if( $response->getStatusCode() == 200){
            $data = json_decode($response->getContent(), true);
            
            $session->set('id', $data['data']["id"]);
           
            $responseUser = $this->client->request(
                'GET',
                $this->apiUrl . "/users/" . $data['data']['id']
            );
            
            $userjson = json_decode($responseUser->getContent(),true);
        
            $session->set("user", $userjson);
         
           
        }else{
           
            // Authentification failed
            $this->addFlash('error', 'Indentifiants invalides');
      
        }
        return $this->redirectToRoute('homepage');

        
    }
    #[Route('/confirmation-email/{token}' , name: 'confirm_email')]
    public function confirmationEmail($token){
        dd('coucou');
        return $this->redirectToRoute('homepage');
    }

    #[Route('mot-de-passe-oublie', name:'forget_password')]
    public function forgetPassword(Request $request){
       
        $data = json_decode($request->getContent(),true);
        
        $email = $data['email'];
       
        $response = $this->client->request(
            "GET",
            $this->apiUrl."/customers?email=".$email
        );
        $customer = json_decode($response->getContent(),true)['hydra:member'];
       
        if($customer){
            $this->toUser->restPassword($customer[0]['email'], $customer[0]['firstName'],$customer[0]['token']);
            return new JsonResponse([
                'status' => 'success',
                'message' => 'Un mail vous a été envoyé '
            ]);
        }else{
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Cet email n\'est pas associé à un compte'
            ]);
        }
    }
    #[Route('/reinitialiser-mot-de-passe/{token}' , name:'reset_password')]
    public function resetPassword(Request $request,$token){
        $response = $this->client->request(
            'GET',
            $this->apiUrl."/customers?token=".$token
        );
        $customer = json_decode($response->getContent(),true)['hydra:member'];
       // dd($customer);
        if(count($customer) == 1){
            if($request->isMethod('POST')){
                $password = $request->request->get('password');
                $response = $this->client->request(
                    'PUT',
                    $this->apiUrl."/customers/".$customer[0]['id'],
                    [
                        'headers' => [
                            'content-type' => 'application/json'
                        ],
                        'body' => json_encode([
                            'password' => $password
                        ])
                    ]
                );
                if($response->getStatusCode()==200){
                    $this->addFlash(
                        'success',
                       'Votre mot de passe a bien été modifié. Vous pouvez l\'utiliser pour vous connecter'
                    );
                    return $this->redirectToRoute('homepage');

                }
                else{
                    $this->addFlash(
                        'success',
                        'Le lien de réinitialisation du mot de passe n\'est plus valide'
                     );
        
                    return $this->redirect($request->getUri());

                }
            }
            

        }
        return $this->render('front/homepage/reset-password.html.twig',[
            'token' => $token
          
    ]);

    }

    #[Route('/profil', name: 'app_profil')]
    public function profil(): Response
    {
        // profil
        return $this->render('/front/homepage/profil.html.twig', [
            
        ]);
    }

    #[Route('/Découvrir-le-gain' , name:"getNumber")]

    public function getNumberCOde(Request $request, Session $session){
        
        $user = $session->get('id');
        
        if($user){
            $data = json_decode($request->getContent(), true);

            $numberCode = $data["numberCode"];

            $responseNumber = $this->client->request(
            'GET',
            $this->apiUrl . "/tickets?numberTicket=".$numberCode ."&status=active" 
            );
            $number = json_decode($responseNumber->getContent(), true)['hydra:member'];

            if($number != null){
             
                $bodyParam =[
                    "status" => "desactive",
                    "customer" => "/api/customers/$user"
                ];
           
                $response = $this->client->request(
                    'PUT',
                    $this->apiUrl . '/tickets/' . $number[0]['id'] ,
                    [
                        'headers' => [
                            'content-type' => 'application/json'
                        ],
                        'body' => json_encode($bodyParam)
                    ]
                );
                $codeNumber = json_decode($response->getContent(),true);
                

                return new JsonResponse([
                'status' => 'success',
                'message' => 'Votre inscription a bien été prise en compte',
                "number" => $codeNumber
                ]);

            }
            else{
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Ce code est invalide'
                ]);

            }

        }else{
            return $this->redirectToRoute("homepage");
        }
        

    }

    #[Route('/deconnexion', name:'logout')]
    public function logout(Request $request){
        $session = $request->getSession();
        $session->remove('id');
        $session->remove('user');
        $session->clear();

        return $this->redirectToRoute('homepage');

    }
    


    
   
}
