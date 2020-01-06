<?php
/**
 * Created by PhpStorm.
 * User: sayya
 * Date: 06/01/2020
 * Time: 00:13
 */

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{

    /**
     * @Route("/api/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->json([
            'last_username' => $lastUsername,
            'error' => $error->getMessageKey(),
            'success' => false
        ]);
    }


    /**
     * @return string
     * @throws \Exception
     * generate and api token and makes sure it is unique
     */
    private function generate_api_key(){
        $user_repository = $this->getDoctrine()->getRepository(User::class);

        $token = bin2hex(random_bytes(16)) ;
        while($user_repository->findOneBy(['apiToken'=>$token])) {
            $token = bin2hex(random_bytes(16)) ;
        }
        return $token;
    }


    /**
     * @Route("/api/login_success", name="login_success")
     */
    public function login_success(): Response
    {
        $user = $this->getUser() ;

        if(empty($user)){
            throw new AccessDeniedException("Access Denied");
        }

        //if the user has no API Key , generates one and stores in the database
        if(!($user->getApiToken())){
            $user->setApiToken($this->generate_api_key());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->json([
            'user' =>["email" => $user->getEmail(), "token"=> $user->getApiToken()],
            'success' => true
        ]);
    }
}