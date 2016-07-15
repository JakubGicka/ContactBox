<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
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
     * @Route("/new")
     * @Template("AppBundle:Contact:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $address = new Address();
        
        $form = $this
            ->createFormBuilder($address)
            //->setAction($this->generateUrl('app_contact_create'))
            ->add('city')
            ->add('street')
            ->add('houseNumber')
            ->add('flatNumber')
            ->add('submit', 'submit')
            ->getForm();
        
        $form->handleRequest($request);   //pobranie pól i wypełnienie contactu tym, co przyszło w formularzu
        
        if ($form->isValid()) {
            $em = $this
                ->getDoctrine()
                ->getManager();
            
            $em->persist($address);
            $em->flush();
            
            return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
        }
        
        return ['form' => $form->createView()];
        
    }
    
    
}
