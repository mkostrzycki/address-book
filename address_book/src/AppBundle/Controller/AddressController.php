<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/address")
 */
class AddressController extends Controller
{
    /**
     * @Route("/{contactId}/new")
     * @Template("AppBundle:Address:new.html.twig")
     * @param Request $request
     * @param $contactId
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request, $contactId)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')->find($contactId);

        $address = new Address();

        $form = $this->createForm('AppBundle\Form\AddressType', $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $address->setContact($contact);
            $contact->addAddress($address);

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute('app_contact_show', ['id' => $contactId]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{contactId}/{addressId}/modify")
     * @param Request $request
     * @param $contactId
     * @param $addressId
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function modifyAction(Request $request, $contactId, $addressId)
    {
        $address = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Address')->find($addressId);

        $form = $this->createForm('AppBundle\Form\AddressType', $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute('app_contact_show', ['id' => $contactId]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{contactID}/{addressId}/delete")
     * @param $contactId
     * @param $addressId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($contactId, $addressId)
    {
        $address = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Address')->find($addressId);

        if (!$address) {
            throw $this->createNotFoundException('Address not found');
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $em->remove($address);
        $em->flush();

        return $this->redirectToRoute('app_contact_show', ['id', $contactId]);
    }
}
