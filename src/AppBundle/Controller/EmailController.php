<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Email;
use AppBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/email")
 */
class EmailController extends Controller
{
    /**
     * @Route("/new/{id}")
     * @Template("Email/new.html.twig")
     */
    public function createAction($id, Request $request)
    {
        $email = new Email();
        
        $form = $this
            ->createFormBuilder($email)
            //->setAction($this->generateUrl('app_contact_create'))
            ->add('email')
            ->add('type', 'choice', array('label'=>'Type',
                'choices'=>array('Domowy'=>'domowy', 'Służbowy'=>'służbowy'),))
            ->add('submit', 'submit')
            ->getForm();
        
        $form->handleRequest($request);   //pobranie pól i wypełnienie contactu tym, co przyszło w formularzu
        
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('AppBundle:Contact')->find($id);
        $contact->getId();
        
        $email->setContact($contact);
        $contact->addEmail($email);
        
        if ($form->isValid()) {
            $em = $this
                ->getDoctrine()
                ->getManager();
            
            $em->persist($email);
            $em->persist($contact);
            $em->flush();
            
            return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
        }
        
        return ['form' => $form->createView()];
        
    }
    
    /**
     * @Route("/new/{id}")
     * @Template("Email/new.html.twig")
     */
    public function newAction()
    {
        $email = new Email();
        
        $form = $this
                ->createFormBuilder($email)
                //->setAction($this->generateUrl('app_contact_create'))  //jeśli nie ma action, to wysyła się na tem sam adres
                ->add('email')
                ->add('type', 'choice', array('label'=>'Type',
                    'choices'=>array('Domowy'=>'domowy', 'Służbowy'=>'służbowy'),))
                ->add('submit', 'submit')
                ->getForm();
        
        return ['form' => $form->createView()];
        
    }
    
    /**
     * @Route("/modify/{id}")
     * @Template("Email/modify.html.twig")
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
        
        $email = $this->getDoctrine()->getRepository('AppBundle:Email')->findOneByContact($contact->getId());  //pobranie emailu z kontaktu
        
        $form = $this
                ->createFormBuilder($email)  
                ->add('email')
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
     * @Template("Email/modify.html.twig")
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
     * @Template("Email/delete.html.twig")
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
        
        $email = $this->getDoctrine()->getRepository('AppBundle:Email')->findOneByContact($contact->getId());
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($email);
        $em->flush();
        
        return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
    }
    
}
