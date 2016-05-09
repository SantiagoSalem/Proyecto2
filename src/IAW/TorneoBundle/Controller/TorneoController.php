<?php

namespace IAW\TorneoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use IAW\TorneoBundle\Entity\Torneo;
use IAW\TorneoBundle\Form\TorneoType;

class TorneoController extends Controller
{
    public function indexAction()
    {
      return new Response("Buenas a todos!");
      //return $this->render('IAWTorneoBundle:Default:index.html.twig');
    }

    public function addAction(){
      $fixture = new Torneo();
      $form = $this->createCreateForm($fixture);

      $logInUser = $this->get('security.token_storage')->getToken()->getUser();

      return $this->render('IAWTorneoBundle:Torneo:add.html.twig', array('logInUser' => $logInUser,
                            'form' => $form->createView()));


    }

    private function createCreateForm(Torneo $entity){
        $form = $this->createForm(new TorneoType(),$entity, array(
              'action'=> $this->generateUrl('iaw_torneo_create'),
              'method'=> 'POST'
        ));
        return $form;
    }


}
