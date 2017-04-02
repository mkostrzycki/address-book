<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactGroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ContactGroupController extends Controller
{
    /**
     * @Route("/contactGroup/new")
     * @Template("AppBundle:ContactGroup:new.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {
        $contactGroup = new ContactGroup();

        $form = $this->createForm('AppBundle\Form\ContactGroupType', $contactGroup);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($contactGroup);
            $em->flush();

            return $this->redirectToRoute('app_contactgroup_showall');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/contactGroup/{contactGroupId}/modify")
     * @Template("AppBundle:ContactGroup:modify.html.twig")
     * @param Request $request
     * @param $contactGroupId
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function modifyAction(Request $request, $contactGroupId)
    {
        $contactGroup = $this
            ->getDoctrine()
            ->getRepository('AppBundle:ContactGroup')->find($contactGroupId);

        if (!$contactGroup) {
            throw $this->createNotFoundException('Contact group not found');
        }

        $form = $this->createForm('AppBundle\Form\ContactGroupType', $contactGroup);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->flush();

            return $this->redirectToRoute('app_contactgroup_show', ['contactGroupId' => $contactGroupId]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/contactGroup/{contactGroupId}/delete")
     * @Method("DELETE")
     * @param $contactGroupId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($contactGroupId)
    {
        $contactGroup = $this
            ->getDoctrine()
            ->getRepository('AppBundle:ContactGroup')->find($contactGroupId);

        if (!$contactGroup) {
            throw $this->createNotFoundException('Contact group not found');
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $em->remove($contactGroup);
        $em->flush();

        return $this->redirectToRoute('app_contactgroup_showall');
    }

    /**
     * @Route("/contactGroup/{contactGroupId}")
     * @Template("@App/ContactGroup/show.html.twig")
     * @param $contactGroupId
     * @return array
     */
    public function showAction($contactGroupId)
    {
        $contactGroups = $this
            ->getDoctrine()
            ->getRepository('AppBundle:ContactGroup')->findAll();

        $contactGroup = $this
            ->getDoctrine()
            ->getRepository('AppBundle:ContactGroup')->find($contactGroupId);

        if (!$contactGroup) {
            throw $this->createNotFoundException('Contact group not found');
        }

        return [
            'contactGroups' => $contactGroups,
            'contactGroup' => $contactGroup
        ];
    }

    /**
     * @Route("/")
     * @Template("AppBundle:ContactGroup:showAll.html.twig")
     * @return array
     */
    public function showAllAction()
    {
        $contactGroups = $this
            ->getDoctrine()
            ->getRepository('AppBundle:ContactGroup')->findAll();

        $contacts = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')->findAll();

        return [
            'contactGroups' => $contactGroups,
            'contacts' => $contacts
        ];
    }
}
