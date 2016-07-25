<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Group;
use AppBundle\Entity\Band;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/contact")
 */
class ContactController extends Controller
{
    /**
     * @Route("/new")
     * @Template("Contact/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $contact = new Contact();
        
        $form = $this
            ->createFormBuilder($contact)
            //->setAction($this->generateUrl('app_contact_create'))
            ->add('name')
            ->add('surname')
            ->add('description')
            ->add('submit', 'submit')
            ->getForm();
        
        $form->handleRequest($request);   //pobranie pól i wypełnienie contactu tym, co przyszło w formularzu
        
        if ($form->isValid()) {
            $em = $this
                ->getDoctrine()
                ->getManager();
            
            $em->persist($contact);
            $em->flush();
            
            return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
        }
        
        return ['form' => $form->createView()];
        
    }
    
    /**
     * @Route("/new")
     * @Template("Contact/new.html.twig")
     */
    public function newAction()
    {
        $contact = new Contact();
        
        $form = $this
                ->createFormBuilder($contact)
                //->setAction($this->generateUrl('app_contact_create'))  //jeśli nie ma action, to wysyła się na tem sam adres
                ->add('name')
                ->add('surname')
                ->add('description')
                ->add('submit', 'submit')
                ->getForm();
        
        return ['form' => $form->createView()];
        
    }
    
    /**
     * @Route("/modify/{id}")
     * @Template("Contact/modify.html.twig")
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
        
        $form = $this
                ->createFormBuilder($contact)  
                ->add('name')
                ->add('surname')
                ->add('description')
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
     * @Template("Contact/modify.html.twig")
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
     * @Template("Contact/delete.html.twig")
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
        $em = $this->getDoctrine()->getManager();
        $em->remove($contact);
        $em->flush();
        
        return $this->redirectToRoute('app_contact_showall');
    }
    
    /**
     * @Route("/show/{id}")
     * @Template("Contact/show.html.twig")
     */
    public function showAction($id)
    { 
        $contact = $this->getDoctrine()->getRepository('AppBundle:Contact')->find($id);
        
        if (!$contact) {          
            throw $contact->createNotFoundException('Contact not found');
        }
        
        $address = $this->getDoctrine()->getRepository('AppBundle:Address')->findOneByContact($contact->getId());
        $email = $this->getDoctrine()->getRepository('AppBundle:Email')->findOneByContact($contact->getId());
        $phone = $this->getDoctrine()->getRepository('AppBundle:Phone')->findOneByContact($contact->getId());
        
        return ['contact' => $contact, 'address' => $address, 'email' => $email, 'phone' => $phone];
        
        
//        return ['contact' => 
//            $this
//            ->getDoctrine()
//            ->getRepository('AppBundle:Contact')
//            ->find($id)
//        ];
    }
    
    /**
     * @Route("/showAll")
     * @Template("Contact/showAll.html.twig")
     */
    public function showAllAction()
    {
        return ['contacts' => 
            $this
            ->getDoctrine()
            ->getRepository('AppBundle:Contact')
            ->findAll()
        ];
    }
    

}
