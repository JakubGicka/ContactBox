<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Contacts_Groups;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/group")
 */
class GroupController extends Controller
{
    /**
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/new/{id}")
     * @Template("Group/new.html.twig")
     */
    public function initFixtures($id, Request $request)
    {     
        $group = new Group();
        
        $form = $this
            ->createFormBuilder($group)
            //->setAction($this->generateUrl('app_contact_create'))
            ->add('name', 'entity', array('class' => 'AppBundle:Group', 'choice_label' => 'name'))
            ->add('submit', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        
//        $contact = new Contact();
//        $contact->setName('Ktoś');
//        $contact->setSurname('JK');
//        $contact->setDescription('On');
        
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('AppBundle:Contact')->findById(19);
        
        $contacts_groups = new Contacts_Groups();
//        $contacts_groups->setContact_id(22);
//        $contacts_groups->setGroup_id(1);
        
        $contacts_groups->setContact($contact);
        $contacts_groups->setGroup($group);
//        $group->addContact($contact);
//        $contact->addGroup($group);
//        
        //$em = $this->getDoctrine()->getManager();
        //$group->getContacts()->add($this->$contact->getId());
        
        if ($form->isValid()) {
            $em = $this
                ->getDoctrine()
                ->getManager();
            
            $em->persist($group);
            $em->persist($contact);
            $em->persist($contacts_groups);
            $em->flush();
            
            return $this->redirectToRoute('app_contact_show', ['id' => $contact->getId()]);
        }
        
        return array('group' => $group,'form' => $form->createView());
        
        
        //return new Response('OK');
        
    }
    
    
    
    
}
