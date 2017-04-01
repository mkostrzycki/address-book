<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EmailAddress;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class EmailAddressController extends Controller
{
    /**
     * @Route("/{contactId}/new")
     * @Template("AppBundle:EmailAddress:new.html.twig")
     * @param Request $request
     * @param $contactId
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request, $contactId)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')->find($contactId);

        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        $emailAddress = new EmailAddress();

        $form = $this->createForm('AppBundle\Form\EmailAddressType', $emailAddress);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contact->addEmailAddress($emailAddress);
            $emailAddress->setContact($contact);

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($emailAddress);
            $em->flush();

            return $this->redirectToRoute('app_contact_show', ['id' => $contactId]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{contactId}/{emailAddressId}/modify")
     * @Template("AppBundle:EmailAddress:modify.html.twig")
     * @param Request $request
     * @param $contactId
     * @param $emailAddressId
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function modifyAction(Request $request, $contactId, $emailAddressId)
    {
        $emailAddress = $this
            ->getDoctrine()
            ->getRepository('AppBundle:EmailAddress')->find($emailAddressId);

        if (!$emailAddress) {
            throw $this->createNotFoundException('Email address not found');
        }

        $form = $this->createForm('AppBundle\Form\EmailAddressType', $emailAddress);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->flush();

            return $this->redirectToRoute('app_contact_show', ['id' => $contactId]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("{contactId}/{emailAddressId}/delete")
     * @Method("DELETE")
     * @param $contactId
     * @param $emailAddressId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($contactId, $emailAddressId)
    {
        $emailAddress = $this
            ->getDoctrine()
            ->getRepository('AppBundle:EmailAddress')->find($emailAddressId);

        if (!$emailAddress) {
            throw $this->createNotFoundException('Email address not found');
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $em->remove($emailAddress);
        $em->flush();

        return $this->redirectToRoute('app_contact_show', ['id' => $contactId]);
    }
}
