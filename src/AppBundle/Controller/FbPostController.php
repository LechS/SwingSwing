<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\FbPost;
use AppBundle\Form\FbPostType;

/**
 * FbPost controller.
 *
 * @Route("/fbpost")
 */
class FbPostController extends Controller
{
    /**
     * Lists all FbPost entities.
     *
     * @Route("/", name="fbpost_index")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $fbPosts = $em->getRepository('AppBundle:FbPost')->findBy(['user' => $user]);

        return $this->render('fbpost/index.html.twig', array(
            'fbPosts' => $fbPosts,
        ));
    }

    /**
     * Creates a new FbPost entity.
     *
     * @Route("/new", name="fbpost_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();

        if($request->isMethod('POST')){
            $post = $request->request;

            if($this->get('app.save.post')->savePost($user, $post)){
                return new JsonResponse(['success' => true]);
            };
            return new JsonResponse(['success' => false]);
        }

        $fbPost = new FbPost();
        $form = $this->createForm('AppBundle\Form\FbPostType', $fbPost);
        $form->handleRequest($request);

        $endpoints = $this->getDoctrine()->getRepository('AppBundle:FbEndpoint')->findAll();

        return $this->render('fbpost/new.html.twig', array(
            'fbPost' => $fbPost,
            'form' => $form->createView(),
            'endpoints' => $endpoints,
        ));
    }

    /**
     * Finds and displays a FbPost entity.
     *
     * @Route("/{id}", name="fbpost_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(FbPost $fbPost)
    {

        return $this->render('fbpost/show.html.twig', array(
            'fbPost' => $fbPost,

        ));
    }

    /**
     * Displays a form to edit an existing FbPost entity.
     *
     * @Route("/{id}/edit", name="fbpost_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, FbPost $fbPost)
    {
        $user = $this->getUser();

        $editForm = $this->createForm('AppBundle\Form\FbPostType', $fbPost);

        if($request->isMethod('POST')){
            $post = $request->request;

            if($this->get('app.save.post')->editPost($user, $post, $fbPost)){
                return new JsonResponse(['success' => true]);
            };
            return new JsonResponse(['success' => false]);
        }

        $endpoints = $this->getDoctrine()->getRepository('AppBundle:FbEndpoint')->findAll();

        $checkedEndpointsObj = $fbPost->getFbEndpoints();

        $checkedEndpoints = [];
        foreach ($checkedEndpointsObj as $endpoint){
            $checkedEndpoints[] = $endpoint->getFbId();
        }

        return $this->render('fbpost/edit.html.twig', array(
            'fbPost' => $fbPost,
            'form' => $editForm->createView(),
            'endpoints' => $endpoints,
            'checkedpoints' => $checkedEndpoints,
        ));
    }

    /**
     * Deletes a FbPost entity.
     *
     * @Route("/{id}/usun", name="fbpost_delete")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, FbPost $fbPost)
    {
        $fbPost->setStatus(FbPost::STATUS_DELETED);

        $em = $this->getDoctrine()->getManager();

        $em->persist($fbPost);

        $em->flush();

        return $this->redirectToRoute('fbpost_index');
    }

    /**
     *
     * @Route("/{id}/podobna", name="fbpost_similar")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function similarAction(Request $request, FbPost $fbPost)
    {
        $fbPostNew = $this->get('app.save.post')->similarPost($fbPost);

        return $this->redirectToRoute('fbpost_edit', ['id' => $fbPostNew->getId() ]);
    }

    /**
     *
     * @Route("/{id}/publikuj", name="fbpost_publish")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function publishAction(Request $request, FbPost $fbPost)
    {
        $user = $this->getUser();
        $message = $fbPost->getMessage();
        $link = $fbPost->getLink();

        $endpoints = $fbPost->getFbEndpoints();

//        die('uwaga publikacja!');
        foreach ($endpoints as $endpoint) {
            $this->get('app.facebook')->publish($user->getFacebookLongLivedAccessToken(), $endpoint->getId(), $message, $link);
        }

        return $this->redirectToRoute('fbpost_index');
    }

}
