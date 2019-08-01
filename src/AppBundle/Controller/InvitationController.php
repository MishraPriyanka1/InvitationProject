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
     * @Route("view/{userId}")
     */
    public function getInvitationAction($userId)
    {
	     $em = $this->getDoctrine()->getManager();
	     $query = $em->createQuery("SELECT u.username FROM AppBundle:Users u  WHERE u.id = $userId");
		  $user = $query->getResult();
         echo $user[0]['username'];
		 
	         $invitations = $em->createQuery("SELECT i FROM AppBundle:Invitation i  WHERE i.invitedName = :invitedname");
		     $invitations->setParameter('invitedname',$user[0]['username']);
			 $invited = $invitations->getResult();
		$result_data = (object)[];
		
		if(!$invited){
		$result_data =(object)[
		'status'=> "error"
		];		
		}else{
			['invitations'=> $invited];
		}
        return $this->render('AppBundle:Invitation:receiveinvitation.html.twig',['invitations'=>$invited]);
    }

    /**
     * @Route("/api/getall/{id}", name="all_invitation")
	 * @Method("GET")
     */
    public function getAllInvitationForUserAction($id)
	{
	    $posts = $this->getDoctrine()->getRepository('AppBundle:Invitation')
		         ->findBy(array('userId'=>$id));	
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
     *  @Route("list/{id}", name="list")
	 * 
     */
    public function listAction(Request $request)
	{
	   
	   $em = $this->getDoctrine()->getManager();
	     $query = $em->createQuery("SELECT i FROM AppBundle:Invitation i  WHERE i.senderName LIKE :filtertext");
		 $query->setParameter('filtertext', '%' . $request->query->getAlnum('filter') . '%');
		  $invitation = $query->getResult();

	   
	   	   return $this->render('AppBundle:Invitation:get_all_invitation.html.twig',['posts'=>$invitation]);
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
						 $userId =  $id;
						 
						 $invitation->setSendername($sendername);
						 $invitation->setInvitedname($invitedname);
						 $invitation->setMessage($message);
						 $invitation->setStatus($status);
						 $invitation->setUserId($userId);

						 $em = $this->getDoctrine()->getManager();
						 $em->persist($invitation);
						 $em->flush();

						 $this->addFlash('message', 'Post Created Successfully!');
						 return $this->redirectToRoute('all_invitation',['id'=>$id]);

					  }
        return $this->render('AppBundle:Invitation:create_invitation.html.twig', ['form'=>$form->createView()]);
    }

}
