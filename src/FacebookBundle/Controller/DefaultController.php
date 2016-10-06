<?php

namespace FacebookBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/success/login", name="loginSucces")
     * @Security()
     */
    public function facebookLoginSuccessAction(Request $request)
    {



        return $this->render('FacebookBundle:Default:index.html.twig');
    }
}
