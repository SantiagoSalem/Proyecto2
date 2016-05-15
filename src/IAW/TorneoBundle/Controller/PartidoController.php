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
use IAW\ParticipanteBundle\Entity\Puntaje;

use IAW\TorneoBundle\Form\TorneoType;
use IAW\TorneoBundle\Form\PartidoType;



class PartidoController extends Controller
{

  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    //El primer participante que aparece en la lista es el ultimo insertado.
    $dql = "SELECT p.id, p.equipoLocal, p.equipoVisitante, pp.imagePath as imagenEL, ppp.imagePath as imagenEV
            FROM IAWTorneoBundle:Partido p, IAWParticipanteBundle:Participante pp, IAWParticipanteBundle:Participante ppp
            WHERE p.equipoLocal = pp.name  AND p.equipoVisitante = ppp.name
            ORDER BY p.fecha ASC"; //Me tira error al ordenar por fecha...

    //Ejecuto la consulta
    $partidos = $em->createQuery($dql)->getResult();

    /*Aplico la paginacion con:
          * la consulta ejecutada,
          * empieza desde la pagina 1,
          * y muestro 10 partidos por pagina
    */

    $logInUser = $this->get('security.token_storage')->getToken()->getUser();

    return $this->render('IAWTorneoBundle:Partido:index.html.twig', array('logInUser' => $logInUser,'partidos' => $partidos));

  }

  public function editAction($id){

    $em = $this->getDoctrine()->getManager();
    $partido = $em->getRepository('IAWTorneoBundle:Partido')->find($id);

    //Verifico si existe el usuario con el id indicado
    if(!$partido){
      throw $this->createNotFoundException('Usuario no encontrado');
    }

    $form = $this->createEditForm($partido);

    $logInUser = $this->get('security.token_storage')->getToken()->getUser();

    return $this->render('IAWTorneoBundle:Partido:edit.html.twig', array('logInUser' => $logInUser,'partido' => $partido,'form' => $form->createView()));
  }

  private function createEditForm(Partido $entidad){

    /*Creo el formulario con:
        * El formulario,
        * la entidad,
        * opciones:
            * generar ruta user/update/{id}
            * metodo PUT
    */
    $form = $this->createForm(new PartidoType(), $entidad, array(
          'action' => $this->generateUrl('iaw_partido_update', array('id' => $entidad->getId())),
          'method' => 'PUT'));
    return $form;
  }

  public function updateAction($id, Request $request){

    $em = $this->getDoctrine()->getManager();

    //Obtengo el usuario con el id indicado
    $partido = $em->getRepository('IAWTorneoBundle:Partido')->find($id);

    //Verifico si existe el usuario con el id indicado
    if(!$partido){
      throw $this->createNotFoundException('Partido no encontrado');
    }

    //Obtengo el formulario procesandolo con el objeto request
    $form = $this->createEditForm($partido);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){

      $equipoLocal = $form->get('equipoLocal')->getData();
      $equipoVisitante = $form->get('equipoVisitante')->getData();
      $golesEquipoLocal = $form->get('golesEquipoLocal')->getData();
      $golesEquipoVisitante = $form->get('golesEquipoVisitante')->getData();

      if($golesEquipoLocal == $golesEquipoVisitante){
        $puntajeLocal = $em->getRepository('IAWParticipanteBundle:Puntaje')->find($equipoLocal);
        $puntajeVisitante= $em->getRepository('IAWParticipanteBundle:Puntaje')->find($equipoVisitante);

        $pj = $puntajeLocal->getPj();
        $pe = $puntajeLocal->getPe();
        $Gf = $puntajeLocal->getGf();
        $Gc = $puntajeLocal->getGc();
        $Dg = $puntajeLocal->getDg();

        $puntajeLocal->setPj($pj++);
        $puntajeLocal->setPe($pe++);
        $puntajeLocal->setGf($gf + $golesEquipoLocal);
        $puntajeLocal->setGc($gc + $golesEquipoVisitante);

        $Gf = $puntajeLocal->getGf();
        $Gc = $puntajeLocal->getGc();

        $puntajeLocal->setDg($gf- $gc);

        $pj = $puntajeVisitante->getPj();
        $pe = $puntajeVisitante->getPe();
        $Gf = $puntajeVisitante->getGf();
        $Gc = $puntajeVisitante->getGc();
        $Dg = $puntajeVisitante->getDg();

        $puntajeVisitante->setPj($pj++);
        $puntajeVisitante->setPe($pe++);
        $puntajeVisitante->setGf($gf + $golesEquipoVisitante);
        $puntajeVisitante->setGc($gc + $golesEquipoLocal);

        $Gf = $puntajeVisitante->getGf();
        $Gc = $puntajeVisitante->getGc();

        $puntajeVisitante->setDg($gf- $gc);

        $em->flush();
      }
      if($golesEquipoLocal > $golesEquipoVisitante){
        $puntajeLocal = $em->getRepository('IAWParticipanteBundle:Puntaje')->find($equipoLocal);
        $puntajeVisitante= $em->getRepository('IAWParticipanteBundle:Puntaje')->find($equipoVisitante);

        $pj = $puntajeLocal->getPj();
        $pg = $puntajeLocal->getPg();
        $Gf = $puntajeLocal->getGf();
        $Gc = $puntajeLocal->getGc();
        $Dg = $puntajeLocal->getDg();

        $puntajeLocal->setPj($pj++);
        $puntajeLocal->setPg($pg++);
        $puntajeLocal->setGf($gf + $golesEquipoLocal);
        $puntajeLocal->setGc($gc + $golesEquipoVisitante);

        $Gf = $puntajeLocal->getGf();
        $Gc = $puntajeLocal->getGc();

        $puntajeLocal->setDg($gf- $gc);

        $pj = $puntajeVisitante->getPj();
        $pp = $puntajeVisitante->getPp();
        $Gf = $puntajeVisitante->getGf();
        $Gc = $puntajeVisitante->getGc();
        $Dg = $puntajeVisitante->getDg();

        $puntajeVisitante->setPj($pj++);
        $puntajeVisitante->setPp($pp++);
        $puntajeVisitante->setGf($gf + $golesEquipoVisitante);
        $puntajeVisitante->setGc($gc + $golesEquipoLocal);

        $Gf = $puntajeVisitante->getGf();
        $Gc = $puntajeVisitante->getGc();

        $puntajeVisitante->setDg($gf- $gc);

        $em->flush();


      }
      if($golesEquipoLocal < $golesEquipoVisitante){
        $puntajeLocal = $em->getRepository('IAWParticipanteBundle:Puntaje')->find($equipoLocal);
        $puntajeVisitante= $em->getRepository('IAWParticipanteBundle:Puntaje')->find($equipoVisitante);

        $pj = $puntajeLocal->getPj();
        $pp = $puntajeLocal->getPp();
        $Gf = $puntajeLocal->getGf();
        $Gc = $puntajeLocal->getGc();
        $Dg = $puntajeLocal->getDg();

        $puntajeLocal->setPj($pj++);
        $puntajeLocal->setPp($pp++);
        $puntajeLocal->setGf($gf + $golesEquipoLocal);
        $puntajeLocal->setGc($gc + $golesEquipoVisitante);

        $Gf = $puntajeLocal->getGf();
        $Gc = $puntajeLocal->getGc();

        $puntajeLocal->setDg($gf- $gc);

        $pj = $puntajeVisitante->getPj();
        $pg = $puntajeVisitante->getPg();
        $Gf = $puntajeVisitante->getGf();
        $Gc = $puntajeVisitante->getGc();
        $Dg = $puntajeVisitante->getDg();

        $puntajeVisitante->setPj($pj++);
        $puntajeVisitante->setPg($pg++);
        $puntajeVisitante->setGf($gf + $golesEquipoVisitante);
        $puntajeVisitante->setGc($gc + $golesEquipoLocal);

        $Gf = $puntajeVisitante->getGf();
        $Gc = $puntajeVisitante->getGc();

        $puntajeVisitante->setDg($gf- $gc);

        $em->flush();


      }





      //Guardo en la base de datos
      $em->flush();

      //Genero el mensaje para mostrar
      $this->addFlash('mensaje', "Partido actualizado correctamente");

      return $this->redirectToRoute('iaw_partido_index');
    }
    $logInUser = $this->get('security.token_storage')->getToken()->getUser();

    //En caso de algun problema, renderizo el formulario
    return $this->render('IAWTorneoBundle:Partido:edit.html.twig', array('logInUser' => $logInUser,'partido', 'form' => $form->createView()));
  }



  }
