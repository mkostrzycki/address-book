<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/contact")
 */
class ContactController extends Controller
{
    /**
     * @Route("/new")
     * @Template("AppBundle:Contact:new.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createForm('AppBundle\Form\ContactType', $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('app_contact_showall');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{id}/modify")
     * @Template("AppBundle:Contact:modify.html.twig")
     * @return array
     */
    public function modifyAction(Request $request, $id)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')->find($id);

        $form = $this->createForm('AppBundle\Form\ContactType', $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('app_contact_showall');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{id}/delete")
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $em->remove($contact);
        $em->flush();

        return $this->redirectToRoute('app_contact_showall');
    }

    /**
     * @Route("/{id}")
     * @Template("AppBundle:Contact:show.html.twig")
     * @return array
     */
    public function showAction($id)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')->find($id);

        return [
            'contact' => $contact
        ];
    }

    /**
     * @Route("/")
     * @Template("AppBundle:Contact:showAll.html.twig")
     * @return array
     */
    public function showAllAction()
    {
        $contacts = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')->findAll();

        return [
            'contacts' => $contacts
        ];
    }
}
