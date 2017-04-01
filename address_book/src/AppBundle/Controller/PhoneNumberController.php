<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PhoneNumber;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/PhoneNumber")
 */
class PhoneNumberController extends Controller
{
    /**
     * @Route("/{contactId}/new")
     * @Template("AppBundle:PhoneNumber:new.html.twig")
     * @param Request $request
     * @param $contactId
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request, $contactId)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')->find($contactId);

        $phoneNumber = new PhoneNumber();

        $form = $this->createForm('AppBundle\Form\PhoneNumberType', $phoneNumber);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $phoneNumber->setContact($contact);
            $contact->addPhoneNumber($phoneNumber);

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($phoneNumber);
            $em->flush();

            return $this->redirectToRoute('app_contact_show', ['id' => $contactId]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{contactId}/{phoneNumberId}/modify")
     * @Template("AppBundle:PhoneNumber:modify.html.twig")
     * @param Request $request
     * @param $contactId
     * @param $phoneNumberId
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function modifyAction (Request $request, $contactId, $phoneNumberId)
    {
        $phoneNumber = $this
            ->getDoctrine()
            ->getRepository('AppBundle:PhoneNumber')->find($phoneNumberId);

        $form = $this->createForm('AppBundle\Form\PhoneNumberType', $phoneNumber);

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
     * @Route("/{contactId}/{phoneNumberId}/delete")
     * @Method("DELETE")
     * @param $contactId
     * @param $phoneNumberId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($contactId, $phoneNumberId)
    {
        $phoneNumber = $this
            ->getDoctrine()
            ->getRepository('AppBundle:PhoneNumber')->find($phoneNumberId);

        if (!$phoneNumber) {
            throw $this->createNotFoundException('Phone number not found');
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $em->remove($phoneNumber);
        $em->flush();

        return $this->redirectToRoute('app_contact_show', ['id' => $contactId]);
    }
}
