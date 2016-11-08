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
     * @Security("has_role('ROLE_USER')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function facebookLoginSuccessAction(Request $request)
    {
        if(!$loggedUser = $this->getUser()){
            return $this->redirect('homepage');
        }

        $fbService = $this->get('app.facebook');
        $fbService->setLongLivedAccessToken($loggedUser);

        $checkPerms = $fbService->checkPermissions($fbService->getPermissions($loggedUser));

        if(!$checkPerms){
            $this->get('security.token_storage')->setToken(null);
            $request->getSession()->invalidate();

            $this->addFlash('notice', 'Zaloguj się ponownie i nadaj aplikacji wszystkie uprawnienia');
        }

        return $this->redirectToRoute('fbpost_index');
    }
}
