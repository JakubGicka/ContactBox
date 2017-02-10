<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Phone;
use AppBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/phone")
 */
class PhoneController extends Controller
{
    /**
     * @Route("/new/{id}")
     * @Template("Phone/new.html.twig")
     */
    public function createAction($id, Request $request)
    {
        $phone = new Phone();
        
        $form = $this
            ->createFormBuilder($phone)
            ->add('phoneNumber')
            ->add('type', 'choice', array('label'=>'Type',
                'choices'=>array('Domowy'=>'domowy', 'Służbowy'=>'służbowy'),))
            ->add('submit', 'submit')
            ->getForm();
        
        $form->handleRequest($request);   
        
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('AppBundle:Contact')->find($id);
        $contact->getId();
        
        $phone->setContact($contact);
        $contact->addPhone($phone);
        
        if ($form->isValid()) {
            $em = $this
                ->getDoctrine()
                ->getManager();
            
            $em->persist($phone);
            $em->persist($contact);
            $em->flush();
            
            return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
        }
        
        return ['form' => $form->createView()];
        
    }
    
    /**
     * @Route("/new/{id}")
     * @Template("Phone/new.html.twig")
     */
    public function newAction()
    {
        $phone = new Phone();
        
        $form = $this
                ->createFormBuilder($phone)
                ->add('phoneNumber')
                ->add('type', 'choice', array('label'=>'Type',
                    'choices'=>array('Domowy'=>'domowy', 'Służbowy'=>'służbowy'),))
                ->add('submit', 'submit')
                ->getForm();
        
        return ['form' => $form->createView()];
        
    }
    
    /**
     * @Route("/modify/{id}")
     * @Template("Phone/modify.html.twig")
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
        
        $phone = $this->getDoctrine()->getRepository('AppBundle:Phone')
            ->findOneByContact($contact->getId());  //pobranie telefonu z kontaktu
        
        $form = $this
                ->createFormBuilder($phone)  
                ->add('phoneNumber')
                ->add('type', 'choice', array('label'=>'Type',
                    'choices'=>array('Domowy'=>'domowy', 'Służbowy'=>'służbowy'),))
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
     * @Template("Phone/modify.html.twig")
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
     * @Template("Phone/delete.html.twig")
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
        
        $phone = $this->getDoctrine()->getRepository('AppBundle:Phone')
            ->findOneByContact($contact->getId());
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($phone);
        $em->flush();
        
        return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
    }
    
}
