<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class homeController extends AbstractController  {

    /** 
     * Page d'accueil du site
     * 
     * @Route("/", name="homepage")
     * 
     * @return Response
    */
    public function home(){
        return $this->render('home.html.twig');      
    }

}

?>
