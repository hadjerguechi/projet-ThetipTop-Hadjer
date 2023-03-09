<?php 
namespace App\EmailNotification;

use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class ToUser {

    
    private $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    public function confirmEmail($emailAddress, $firstname, $token)
    {
        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@thetiptop.com', 'Thé Tip Top'))
            ->to($emailAddress)
            ->subject('Confirmation de votre adresse email')
            ->htmlTemplate('email/confirm-email.html.twig')
            ->context([
                'firstname' => $firstname,
                'token' => $token
            ]);
            
        $this->mailer->send($email);
    }

    public function restPassword($emailAddress, $firstname, $token){
        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@thetiptop.com', 'Thé Tip Top'))
            ->to($emailAddress)
            ->subject('Mot de passe oublié')
            ->htmlTemplate('email/reset-password.html.twig')
            ->context([
                'firstname'=>$firstname,
                'token'=>$token
            ]);
        $this->mailer->send($email);

    }

}