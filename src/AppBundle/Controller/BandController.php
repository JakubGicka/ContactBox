<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Band;
use AppBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/band")
 */
class BandController extends Controller
{
    /**
     * @Route("/new/{id}")
     * @Template("Band/add.html.twig")
     */
    public function addAction($id, Request $request)
    {
        $band = new Band();
        
        $form = $this
            ->createFormBuilder($band)
            //->setAction($this->generateUrl('app_contact_create'))
            ->add('name', 'choice', array('label'=>'Name',
                'choices'=>array('Rodzina'=>'Rodzina', 
                    'Znajomi'=>'Znajomi', 
                    'Praca'=>'Praca',
                    ),))
            ->add('submit', 'submit')
            ->getForm();
        
        $form->handleRequest($request);   //pobranie pól i wypełnienie contactu tym, co przyszło w formularzu
        
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('AppBundle:Contact')->find($id);
        $contact->getId();
        
        //$band->addContact($contact);
        $contact->addBand($band);   //to jest dobrze
        
        if ($form->isValid()) {
            $em = $this
                ->getDoctrine()
                ->getManager();
            
            $em->persist($contact);
            //$em->persist($contact);
            $em->flush();
            
            return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
        }
        
        return ['form' => $form->createView()];
    
    }
    
    /**
     * @Route("/show/{id}")
     * @Template("Band/show.html.twig")
     */
    public function showAction($id)
    { 
        $band = $this->getDoctrine()->getRepository('AppBundle:Band')->find($id);
        
        if (!$band) {          
            throw $band->createNotFoundException('Band not found');
        }
        
        return ['band' => $band];
        
    }
    
    /**
     * @Route("/showAll")
     * @Template("Band/showAll.html.twig")
     */
    public function showAllAction()
    {
        return ['bands' => 
            $this
            ->getDoctrine()
            ->getRepository('AppBundle:Band')
            ->findAll()
        ];
    }
        
}
