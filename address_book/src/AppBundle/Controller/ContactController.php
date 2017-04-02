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

            return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{id}/modify")
     * @Template("AppBundle:Contact:modify.html.twig")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
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

        if (!$contact) {
            throw $this->createNotFoundException('No contact found');
        }

        // for adding contact to group
        /** @ToDo: Get only the groups, that contact not belong to */
        $contactGroups = $this
            ->getDoctrine()
            ->getRepository('AppBundle:ContactGroup')->findAll();

        return [
            'contact' => $contact,
            'contactGroups' => $contactGroups
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

    /**
     * @Route("/contactGroup/add")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @internal param integer $contactId
     * @internal param integer $contactGroupId
     */
    public function addToGroup(Request $request)
    {
        $contactId = $request->request->get('contactId');
        $contactGroupId = $request->request->get('contactGroupId');

        $contact = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')->find($contactId);

        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        $contactGroup = $this
            ->getDoctrine()
            ->getRepository('AppBundle:ContactGroup')->find($contactGroupId);

        if (!$contactGroup) {
            throw $this->createNotFoundException('Contact group not found');
        }

        $contact->addContactGroup($contactGroup);
        $contactGroup->addContact($contact);

        $em = $this
            ->getDoctrine()
            ->getManager();

        $em->flush();

        return $this->redirectToRoute('app_contact_show', ['id' => $contactId]);
    }

    /**
     * @Route("/contactGroup/remove")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeFromGroup(Request $request)
    {
        $contactId = $request->request->get('contactId');
        $contactGroupId = $request->request->get('contactGroupId');

        $contact = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')->find($contactId);

        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        $contactGroup = $this
            ->getDoctrine()
            ->getRepository('AppBundle:ContactGroup')->find($contactGroupId);

        if (!$contactGroup) {
            throw $this->createNotFoundException('Contact group not found');
        }

        $contact->removeContactGroup($contactGroup);
        $contactGroup->removeContact($contact);

        $em = $this
            ->getDoctrine()
            ->getManager();

        $em->flush();

        return $this->redirectToRoute('app_contact_show', ['id' => $contactId]);
    }
}
