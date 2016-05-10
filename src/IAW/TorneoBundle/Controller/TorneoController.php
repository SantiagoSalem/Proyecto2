<?php

namespace IAW\TorneoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use IAW\TorneoBundle\Entity\Torneo;
use IAW\TorneoBundle\Entity\Partido;
use IAW\TorneoBundle\Entity\FechaTorneo;
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

          //Obtengo todos los participantes.
          $dql = "SELECT p.name FROM IAWParticipanteBundle:Participante p";
          //Ejecuto la consulta
          $participantes = $em->createQuery($dql)->getResult();

          $dql = "SELECT COUNT(p.name) FROM IAWParticipanteBundle:Participante p";

          $nroParticipantes = $em->createQuery($dql)->getSingleScalarResult();

          //Obtengo la fecha que se ingreso en el formulario
          $fechaInicio = $form->get('fechaInicio')->getData();
          $fechaDelPartido = new \DateTime($fechaInicio->format('Y-m-d'));

          $fechasArr = array();

          //Genero las fecha del torneo, o sea fecha 1, fecha 2 ...
          for ($i=0; $i < $nroParticipantes - 1; $i++) {
            $fecha_torneo = new FechaTorneo();
            $fecha_torneo->setDate($fechaDelPartido);
            $fecha_torneo->setTorneo($torneo);

            array_push($fechasArr,$fecha_torneo);

            $em->persist($fecha_torneo);
            $em->flush();

            //La proxima fecha es a los 7 dias posteriores
            $fechaDelPartido->modify('+7 day');
          }

          $equiposPorFecha = array();

          foreach($fechasArr as $f){
            $equiposPorFecha[] = array();
          }



          $matchs = array();


          foreach($participantes as $k){
            foreach($participantes as $j){
                if($k["name"] == $j["name"]){
                        continue;
                }
                $z = array($k["name"],$j["name"]);
                $z2=array_reverse($z);
                if(!in_array($z2,$matchs)){
                        $matchs[] = $z;

                        $partido = new Partido();
                        $nroFecha = 0;
                        foreach($equiposPorFecha as $ef){
                          if(in_array($k["name"],$ef) || in_array($j["name"],$ef) ){
                            $nroFecha++;
                          }
                          else{
                            break;
                          }
                        }
                        $partido->setFecha($fechasArr[$nroFecha]);
                        $partido->setEquipoLocal($z[0]);
                        $partido->setEquipoVisitante($z[1]);

                        array_push($equiposPorFecha[$nroFecha],$k["name"]);
                        array_push($equiposPorFecha[$nroFecha],$j["name"]);

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
