<?php

namespace FacebookBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * @Route("/success/login", name="loginSuccess")
     *
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function facebookLoginSuccessAction()
    {
        if(!$loggedUser = $this->getUser()){
            return $this->redirect('homepage');
        }

        $fbService = $this->get('app.facebook');
        $fbService->setLongLivedAccessToken($loggedUser);
        $fbService->checkPermissions($fbService->getPermissions($loggedUser));

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/publication/test", name="publicationTest")
     */
    public function facebookPublicationTest(Request $request)
    {
        if(!$loggedUser = $this->getUser()){
            return $this->redirect('homepage');
        }

        $facebookService = $this->get('app.facebook');
        $facebookService->publicationTest($loggedUser);

        return $this->render('FacebookBundle:Default:index.html.twig');
    }
    
    /**
     * @Route("/search/test", name="searchTest")
     */
    public function searchAction(Request $request)
    {
        if(!$loggedUser = $this->getUser()){
            return $this->redirect('homepage');
        }

        $searchString = '';
        
        $facebookService = $this->get('app.facebook');
        $facebookService->search($loggedUser, $searchString);

        return $this->render('FacebookBundle:Default:index.html.twig');
    }
}
