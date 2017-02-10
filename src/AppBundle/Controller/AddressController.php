<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/address")
 */
class AddressController extends Controller
{
    /**
     * @Route("/new/{id}")
     * @Template("Address/new.html.twig")
     */
    public function createAction($id, Request $request)
    {
        $address = new Address();
        
        $form = $this
            ->createFormBuilder($address)
            ->add('city')
            ->add('street')
            ->add('houseNumber')
            ->add('flatNumber')
            ->add('submit', 'submit')
            ->getForm();
        
        $form->handleRequest($request);   
        
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('AppBundle:Contact')->find($id);
        $contact->getId();
        
        $address->setContact($contact);
        $contact->addAddress($address);
        
        if ($form->isValid()) {
            $em = $this
                ->getDoctrine()
                ->getManager();
            
            $em->persist($address);
            $em->persist($contact);
            $em->flush();
            
            return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
        }
        
        return ['form' => $form->createView()];
        
    }
    
    /**
     * @Route("/new/{id}")
     * @Template("Address/new.html.twig")
     */
    public function newAction()
    {
        $address = new Address();
        
        $form = $this
                ->createFormBuilder($address)
                ->add('city')
                ->add('street')
                ->add('houseNumber')
                ->add('flatNumber')
                ->add('submit', 'submit')
                ->getForm();
        
        return ['form' => $form->createView()];
        
    }
    
    /**
     * @Route("/modify/{id}")
     * @Template("Address/modify.html.twig")
     */
    public function updateAction($id, Request $request)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')
            ->find($id);
                
        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }
        
        $address = $this->getDoctrine()->getRepository('AppBundle:Address')
            ->findOneByContact($contact->getId());  //pobranie adresu z kontaktu
        
        $form = $this
                ->createFormBuilder($address)  
                ->add('city')
                ->add('street')
                ->add('houseNumber')
                ->add('flatNumber')
                ->add('submit', 'submit')
                ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $this
                ->getDoctrine()
                ->getManager()
                ->flush();
                
            return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
        }
        
        return ['form' => $form->createView()];
    }
    
    /**
     * @Route("/modify/{id}")
     * @Template("Address/modify.html.twig")
     */
    public function modifyAction()
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')
            ->find($id);
                
        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }
        
        if ($form->isValid()) {
            $this
                ->getDoctrine()
                ->getManager()
                ->flush();
                
            return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
        }
        
        return ['form' => $form->createView()];
        
    }
    
    /**
     * @Route("/delete/{id}")
     * @Template("Address/delete.html.twig")
     */
    public function deleteAction($id)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')
            ->find($id);
                
        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }
        
        $address = $this->getDoctrine()->getRepository('AppBundle:Address')
            ->findOneByContact($contact->getId());
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();
        
        return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
    }
    
}
