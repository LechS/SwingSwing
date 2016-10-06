<?php

namespace FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/success/login", name="loginSucces")
     */
    public function facebookLoginSuccessAction(Request $request)
    {
        if(!$loggedUser = $this->getUser()){
            throw new Exception('No access');
        }

        die('asdasda');

        return $this->render('FacebookBundle:Default:index.html.twig');
    }
}
