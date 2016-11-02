<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\FbEndpoint;
use AppBundle\Form\FbEndpointType;

/**
 * FbEndpoint controller.
 *
 * @Route("/fbendpoint")
 */
class FbEndpointController extends Controller
{
    /**
     * Lists all FbEndpoint entities.
     *
     * @Route("/", name="fbendpoint_index")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $fbEndpoints = $em->getRepository('AppBundle:FbEndpoint')->findAll();

        return $this->render('fbendpoint/index.html.twig', array(
            'fbEndpoints' => $fbEndpoints,
        ));
    }

    /**
     * Creates a new FbEndpoint entity.
     *
     * @Route("/new", name="fbendpoint_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $fbEndpoint = new FbEndpoint();
        $user = $this->getUser();
        $form = $this->createForm('AppBundle\Form\FbEndpointType', $fbEndpoint, [
            'allow_extra_fields' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->get('search')->getData();
            $type = $form->get('type')->getData();

            $results = $this->get('app.facebook')->search($user, $search, $type);

            return $this->render('fbendpoint/search_results.html.twig',[
                'results' => $results['data'],
                'type' => $type,
            ]);
        }



        return $this->render('fbendpoint/new.html.twig', array(
            'fbEndpoint' => $fbEndpoint,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new FbEndpoint entity.
     *
     * @Route("/add", name="fbendpoint_add")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function addAction(Request $request)
    {
        $post = $request->request;
        $checkedValues = $post->get('checkedValues');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        foreach ($checkedValues as $value){
            $values = explode('+', $value);
            $fbId = $values[0];
            $fbName = $values[1];
            $type = $values[2];

            $endpoint = $em->getRepository('AppBundle:FbEndpoint')->findOneBy(['fbId' => $fbId]);

            if($endpoint === null) {
                $endpoint = new FbEndpoint();
                $endpoint->setFbId($fbId);
                $endpoint->setName($fbName);
                $endpoint->setType($type);
            }

            $endpointUsers = $endpoint->getUsers();

            $usersArray = [];
            if(!$endpointUsers->isEmpty()) {
                foreach ($endpointUsers as $user) {
                    $usersArray[] = $user;
                }
            }

            if(!in_array($user, $usersArray)){
                $endpoint->addUser($user);
                $em->persist($endpoint);
                $em->persist($user);
            }

        }
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    /**
     * Finds and displays a FbEndpoint entity.
     *
     * @Route("/{id}", name="fbendpoint_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(FbEndpoint $fbEndpoint)
    {
        $deleteForm = $this->createDeleteForm($fbEndpoint);

        return $this->render('fbendpoint/show.html.twig', array(
            'fbEndpoint' => $fbEndpoint,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing FbEndpoint entity.
     *
     * @Route("/{id}/edit", name="fbendpoint_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, FbEndpoint $fbEndpoint)
    {
        $deleteForm = $this->createDeleteForm($fbEndpoint);
        $editForm = $this->createForm('AppBundle\Form\FbEndpointType', $fbEndpoint);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fbEndpoint);
            $em->flush();

            return $this->redirectToRoute('fbendpoint_edit', array('id' => $fbEndpoint->getId()));
        }

        return $this->render('fbendpoint/edit.html.twig', array(
            'fbEndpoint' => $fbEndpoint,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a FbEndpoint entity.
     *
     * @Route("/{id}", name="fbendpoint_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, FbEndpoint $fbEndpoint)
    {
        $form = $this->createDeleteForm($fbEndpoint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($fbEndpoint);
            $em->flush();
        }

        return $this->redirectToRoute('fbendpoint_index');
    }

    /**
     * Creates a form to delete a FbEndpoint entity.
     *
     * @param FbEndpoint $fbEndpoint The FbEndpoint entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FbEndpoint $fbEndpoint)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fbendpoint_delete', array('id' => $fbEndpoint->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
