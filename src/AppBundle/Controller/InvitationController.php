<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Invitation;
use FOS\RestBundle\Controller as FOSCtrl;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
     * @Route("/api/getall/{id}")
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
		
        return $this->render('AppBundle:Invitation:get_all_invitation.html.twig',['posts'=> $posts]);
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
     * @Route("create")
     */
    public function createInvitationAction()
    {
        return $this->render('AppBundle:Invitation:create_invitation.html.twig', array(
            // ...
        ));
    }

}
