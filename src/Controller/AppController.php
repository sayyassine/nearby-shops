<?php
/**
 * Created by PhpStorm.
 * User: sayya
 * Date: 03/01/2020
 * Time: 16:41
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/" , name="index")
     */
    public function app(){
        return $this->render("base.html.twig") ;
    }
}