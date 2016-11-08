<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\FbPage;
use AppBundle\Form\FbPageType;

/**
 * FbPage controller.
 *
 * @Route("/fbpage")
 */
class FbPageController extends Controller
{
    /**
     * Lists all FbPage entities.
     *
     * @Route("/", name="fbpage_index")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $fbPages = $em->getRepository('AppBundle:FbPage')->findBy(['user' => $user]);

        return $this->render('fbpage/index.html.twig', array(
            'fbPages' => $fbPages,
        ));
    }

    /**
     * Creates a new FbPage entity.
     *
     * @Route("/new", name="fbpage_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $fbPage = new FbPage();
        $user = $this->getUser();
        $form = $this->createForm('AppBundle\Form\FbPageType', $fbPage, [
            'allow_extra_fields' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->get('search')->getData();
            $type = 'page';

            $results = $this->get('app.facebook')->search($user, $search, $type);

            return $this->render('fbendpoint/search_results.html.twig',[
                'results' => $results['data'],
                'type' => $type,
                'pageOwner' => true,
            ]);
        }

        return $this->render('fbpage/new.html.twig', array(
            'fbPage' => $fbPage,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new FbEndpoint entity.
     *
     * @Route("/add", name="fbpage_add")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function addAction(Request $request)
    {
        $post = $request->request;
        $checkedValues = $post->get('checkedValues');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if(!empty($checkedValues)) {
            foreach ($checkedValues as $value) {
                $values = explode('+', $value);
                $fbId = $values[0];
                $fbName = $values[1];
                $type = $values[2];
                $pageData = $this->get('app.facebook')->getPageAccessToken($user, $fbId);

                $accessToken = @$pageData['access_token'];

                if(!$accessToken){
                    return new JsonResponse([
                        'success' => false,
                        'message' => "Nie posiadasz uprawnień do administrowania stroną $fbName",
                    ]);
                }


                $fbPage = $em->getRepository('AppBundle:FbPage')->findOneBy(['user' => $user, 'fbId' => $fbId]);

                if(!$fbPage) {
                    $fbPage = new FbPage();
                }
                $fbPage->setFbId($fbId);
                $fbPage->setName($fbName);
                $fbPage->setUser($user);
                $fbPage->setConfirmed(true);

                $em->persist($fbPage);
            }
            $em->flush();
        }

        return new JsonResponse(['success' => true]);
    }

    /**
     * Finds and displays a FbPage entity.
     *
     * @Route("/{id}", name="fbpage_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(FbPage $fbPage)
    {

        return $this->render('fbpage/show.html.twig', array(
            'fbPage' => $fbPage,
        ));
    }


}
