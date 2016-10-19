<?php

namespace AppBundle\Controller;

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
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $fbPosts = $em->getRepository('AppBundle:FbPost')->findAll();

        return $this->render('fbpost/index.html.twig', array(
            'fbPosts' => $fbPosts,
        ));
    }

    /**
     * Creates a new FbPost entity.
     *
     * @Route("/new", name="fbpost_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $fbPost = new FbPost();
        $form = $this->createForm('AppBundle\Form\FbPostType', $fbPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fbPost);
            $em->flush();

            return $this->redirectToRoute('fbpost_show', array('id' => $fbPost->getId()));
        }

        return $this->render('fbpost/new.html.twig', array(
            'fbPost' => $fbPost,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a FbPost entity.
     *
     * @Route("/{id}", name="fbpost_show")
     * @Method("GET")
     */
    public function showAction(FbPost $fbPost)
    {
        $deleteForm = $this->createDeleteForm($fbPost);

        return $this->render('fbpost/show.html.twig', array(
            'fbPost' => $fbPost,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing FbPost entity.
     *
     * @Route("/{id}/edit", name="fbpost_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FbPost $fbPost)
    {
        $deleteForm = $this->createDeleteForm($fbPost);
        $editForm = $this->createForm('AppBundle\Form\FbPostType', $fbPost);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fbPost);
            $em->flush();

            return $this->redirectToRoute('fbpost_edit', array('id' => $fbPost->getId()));
        }

        return $this->render('fbpost/edit.html.twig', array(
            'fbPost' => $fbPost,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a FbPost entity.
     *
     * @Route("/{id}", name="fbpost_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, FbPost $fbPost)
    {
        $form = $this->createDeleteForm($fbPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($fbPost);
            $em->flush();
        }

        return $this->redirectToRoute('fbpost_index');
    }

    /**
     * Creates a form to delete a FbPost entity.
     *
     * @param FbPost $fbPost The FbPost entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FbPost $fbPost)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fbpost_delete', array('id' => $fbPost->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
