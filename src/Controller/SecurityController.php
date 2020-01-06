<?php
/**
 * Created by PhpStorm.
 * User: sayya
 * Date: 06/01/2020
 * Time: 00:13
 */

namespace App\Controller;


use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
     * @Route("/api/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request ,UserPasswordEncoderInterface $encoder): Response
    {
        $req = json_decode($request->getContent()) ;
        $email = isset($req->email) ? $req->email : ''  ;
        $password = isset($req->password) ? $req->password : ''  ;
        $repeated_password = isset($req->repeated_password) ? $req->repeated_password : ''  ;
        $name = isset($req->name) ? $req->name : ''  ; ;

        $errors = [] ;
        //checking if password is valid
        if( !$password || strlen($password)  < 8 || strlen($password)  > 21   ){
            $errors = ['Missing or invalid password'];
        }

        //checking if password confirmation is valid
        if( $password !== $repeated_password ){
            array_push($errors ,"Passwords doesn't match");
        }

        //checking if name is valid
        if( !$name ){
            array_push($errors ,"Name missing.");
        }

        //creating user object
        $user = new User();
        $user->setEmail($email) ;
        //$user->setName($name) ;
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setApiToken($this->generate_api_key());

        //persisting new user
        if((count($errors) === 0)){
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($user);
            try{
                $em->flush();
            }catch(\Exception $exception){
                //catching unique constraint violation (duplicated email)
                array_push($errors , "Email already used");
            }
        }

        //building response
        $success  = (count($errors) === 0) ;
        $user_object = !$success ? null : [ "user_id" =>  $user->getId(), 'email' => $user->getEmail(), "token" => $user->getApiToken()  ] ;

        return $this->json(["success" => $success , "errors" => $errors , "user" => $user_object ]) ;

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
     * successfully logged in users are redirected here from the Login Form Guard authenticator
     * if the user is authenticated but has no api key a new one is generated and stored
     * returns the api key and the user_id
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


    /**
     * @Route("/api/registration
     */
}