<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Invitation;
use AppBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class InvitationController extends Controller
{
    /**
     * @Route("view")
     */
    public function getInvitationAction($invitationId)
    {
	    $invitation = $this->getDoctrine()
		->getRepository('AppBundle:Invitation')
		->find($invitationId);
		
		$result_data = (object)[];
		
		if(!$invitation){
		$result_data =(object)[
		'status'=> "error"
		];
			
		}
        return $this->render('AppBundle:Invitation:receiveinvitation.html.twig');
    }

    /**
     * @Route("/api/getall/{id}", name="all_invitation")
	 * @Method("GET")
     */
    public function getAllInvitationForUserAction($id)
	{
	    $posts = $this->getDoctrine()->getRepository('AppBundle:Invitation')->findAll();	
		$result_data = (object)[];
		
		if(!$posts){
			$result_data = (object) [
			'status'=>"error"
			];
		}else{
			['posts'=>$posts];
		}		 
	   	   return $this->render('AppBundle:Invitation:get_all_invitation.html.twig',['posts'=>$posts]);
    }

    /**
     * @Route("edit")
     */
    public function editInvitationStatusAction()
    {
        return $this->render('AppBundle:Invitation:edit_invitation_status.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("create/{id}")
     */
    public function createInvitationAction(Request $request,$id)
    {
	     $invitation = new Invitation;
		 $form = $this->createFormBuilder($invitation)
		              ->add('sendername', TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-left:2.5rem')))
					  ->add('invitedname', TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-left:2.5rem')))
					  ->add('message', TextareaType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-left:2.5rem')))
					  ->add('status', TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-left:2.5rem')))
					  ->add('save', SubmitType::class,array('label'=> 'Create invitation', 'attr'=> array('class'=>'btn btn-primary','style'=>'margin-top:10px')))
					  ->getForm();
					  $form->handleRequest($request);
					  if($form->isSubmitted() && $form->isValid()) {
					     $sendername = $form['sendername']->getData();
						 $invitedname = $form['invitedname']->getData();
						 $message = $form['message']->getData();
						 $status = $form['status']->getData();
						 
						 $invitation->setSendername($sendername);
						 $invitation->setInvitedname($invitedname);
						 $invitation->setMessage($message);
						 $invitation->setStatus($status);

						 $em = $this->getDoctrine()->getManager();
						 $em->persist($invitation);
						 $em->flush();

						 $this->addFlash('message', 'Post Created Successfully!');
						 return $this->redirectToRoute('all_invitation',['id'=>$id]);

					  }
        return $this->render('AppBundle:Invitation:create_invitation.html.twig', ['form'=>$form->createView()]);
    }

}
