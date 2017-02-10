<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Band;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Contacts_Bands;
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
     * @Route("/create/{id}")
     * @Template("Band/create.html.twig")
     */
    public function createAction($id, Request $request)
    {
        $newBand = new Band();
        
        $form = $this
            ->createFormBuilder($newBand)
            ->add('name', 'choice', array('label'=>'Name',
                'choices'=>array('Rodzina'=>'Rodzina', 
                    'Znajomi'=>'Znajomi', 
                    'Praca'=>'Praca',
                    ),))
            ->add('submit', 'submit')
            ->getForm();
        
        $form->handleRequest($request);   
          
        $name = $newBand->getName();
        
        $em = $this
                ->getDoctrine()
                ->getManager();
        $band = $em->getRepository('AppBundle:Band')->findOneByName($name);
  
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('AppBundle:Contact')->find($id);
        $contact->getName();
    
        if ($form->isValid()) {
            
            $contacts_bands = new Contacts_Bands();
            $contacts_bands->setContact($contact);
            
            if (!$band) {               
                $contacts_bands->setBand($newBand);
                
                $em = $this
                    ->getDoctrine()
                    ->getManager();
                $em->persist($newBand);
                $em->persist($contact);
                $em->persist($contacts_bands);
                $em->flush();
    
                return $this->redirectToRoute('app_band_show', ['id' => $newBand->getId()]);
            }
            else {
                $contacts_bands->setBand($band);
                
                $em = $this
                    ->getDoctrine()
                    ->getManager();
                $em->persist($band);
                $em->persist($contact);
                $em->persist($contacts_bands);
                $em->flush();
                
                return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
            }
 
        }
        
        return ['form' => $form->createView()];
        
    }
    
    /**
     * @Route("/new")
     * @Template("Band/new.html.twig")
     */
    public function newAction(Request $request)
    {
        $newBand = new Band();
        
        $form = $this
            ->createFormBuilder($newBand)
            ->add('name', 'choice', array('label'=>'Name',
                'choices'=>array('Rodzina'=>'Rodzina', 
                    'Znajomi'=>'Znajomi', 
                    'Praca'=>'Praca',
                    'Inna'=>'Inna',
                    ),))
            ->add('submit', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        
        $name = $newBand->getName();
        
        $em = $this
                ->getDoctrine()
                ->getManager();
        $band = $em->getRepository('AppBundle:Band')->findOneByName($name);
            
        if ($form->isValid()) {
              
            if (!$band) {
                $em = $this
                    ->getDoctrine()
                    ->getManager();
                $em->persist($newBand);
                $em->flush();

                return $this->redirectToRoute('app_band_show', ['name' => $newBand->getName()]);
            }
            else {
                return $this->redirectToRoute('app_band_show', ['name' => $band->getName()]);
            }
            //return $this->redirectToRoute('app_band_add', ['name' => $newBand->getName()]);
        }
  
        return ['form' => $form->createView()];
        
    }
    
    /**
     * @Route("/show/{name}")
     * @Template("Band/show.html.twig")
     */
    public function showAction($name)
    { 
        $band = $this->getDoctrine()->getRepository('AppBundle:Band')->findOneByName($name);
        
        if (!$band) {          
            throw $band->createNotFoundException('Band not found');
        }
        
        $name = $band->getName();
        
        $contacts_bands = $this->getDoctrine()->getRepository('AppBundle:Contacts_Bands')
                ->findByBand($band);
        
        return ['band' => $band, 'contacts_bands' => $contacts_bands];
        
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
