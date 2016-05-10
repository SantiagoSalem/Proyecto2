<?php

namespace IAW\TorneoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use IAW\TorneoBundle\Entity\Torneo;
use IAW\TorneoBundle\Entity\Partido;
use IAW\ParticipanteBundle\Entity\Participante;
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


    public function createAction(Request $request){

      //Obtengo el formulario procesandolo con el objeto request
      $torneo = new Torneo();
      $form = $this->createCreateForm($torneo);
      $form->handleRequest($request);

      if($form->isValid()){

          //Guardo en la base de datos
          $em = $this->getDoctrine()->getManager();
          $em->persist($torneo);
          $em->flush();

          //Obtengo la fecha que se ingreso en el formulario
          $fechaInicio = $form->get('fechaInicio')->getData();
          $fechaDelPartido = new DateTime($fechaInicio);

          //Por ejemplo si quiero sumar dias a la fecha se hace asi
          //$fechaDelPartido->modify('+7 day');

          //Obtengo todos los participantes.
          $dql = "SELECT p.name FROM IAWParticipanteBundle:Participante p";
          //Ejecuto la consulta
          $participantes = $em->createQuery($dql)->getResult();

          $matchs = array();

          foreach($participantes as $k){
            foreach($participantes as $j){
                if($k["name"] == $j["name"]){
                        continue;
                }
                $z = array($k["name"],$j["name"]);
                sort($z);
                if(!in_array($z,$matchs)){
                        $matchs[] = $z;

                        $partido = new Partido();

                        $partido->setEquipoLocal($z[0]);
                        $partido->setEquipoVisitante($z[1]);

                        $em->persist($partido);
                        $em->flush();

                }
            }
          }


        //Genero el mensaje para mostrar
        //$this->addFlash(
        //'mensaje',
        //'Nuevo fixture creado correctamente'
        //);

        return $this->redirectToRoute('iaw_torneo_index');

      }
      $logInUser = $this->get('security.token_storage')->getToken()->getUser();
      //En caso de algun problema, renderizo el formulario
      return $this->render('IAWFixtureBundle:Fixture:add.html.twig', array('logInUser' => $logInUser, 'form' => $form->createView()));
    }




}
