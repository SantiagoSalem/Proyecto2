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

    $logInUser = $this->get('security.token_storage')->getToken()->getUser();

    $em = $this->getDoctrine()->getManager();

    if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') === true) {

      $dql = "SELECT p.id, p.equipoLocal, p.equipoVisitante, IDENTITY(p.fecha) as fecha, pp.imagePath as imagenEL, ppp.imagePath as imagenEV, p.editor
              FROM IAWTorneoBundle:Partido p, IAWParticipanteBundle:Participante pp, IAWParticipanteBundle:Participante ppp
              WHERE p.equipoLocal = pp.name  AND p.equipoVisitante = ppp.name AND p.golesEquipoLocal IS NULL
              ORDER BY p.fecha ASC";
    }
    else{
    //El primer participante que aparece en la lista es el ultimo insertado.
    $dql = "SELECT p.id, p.equipoLocal, p.equipoVisitante, IDENTITY(p.fecha) as fecha, pp.imagePath as imagenEL, ppp.imagePath as imagenEV
            FROM IAWTorneoBundle:Partido p, IAWParticipanteBundle:Participante pp, IAWParticipanteBundle:Participante ppp
            WHERE p.equipoLocal = pp.name  AND p.equipoVisitante = ppp.name AND p.golesEquipoLocal IS NULL AND p.editor = '$logInUser'
            ORDER BY p.fecha ASC"; //Me tira error al ordenar por fecha...
    }
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

  public function verPartidosAction()
  {

    $em = $this->getDoctrine()->getManager();

    //El primer participante que aparece en la lista es el ultimo insertado.
    $dql = "SELECT p.id, p.equipoLocal, p.equipoVisitante, IDENTITY(p.fecha) as fecha, pp.imagePath as imagenEL, ppp.imagePath as imagenEV
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

    return $this->render('IAWTorneoBundle:Partido:index.html.twig', array('partidos' => $partidos));

  }

  public function resultadosPartidosAction()
  {

    $em = $this->getDoctrine()->getManager();

    //El primer participante que aparece en la lista es el ultimo insertado.
    $dql = "SELECT p.id, p.equipoLocal, p.equipoVisitante, IDENTITY(p.fecha) as fecha, pp.imagePath as imagenEL, ppp.imagePath as imagenEV
            FROM IAWTorneoBundle:Partido p, IAWParticipanteBundle:Participante pp, IAWParticipanteBundle:Participante ppp
            WHERE p.equipoLocal = pp.name  AND p.equipoVisitante = ppp.name AND p.golesEquipoLocal IS NOT NULL
            ORDER BY p.fecha ASC"; //Me tira error al ordenar por fecha...

    //Ejecuto la consulta
    $partidos = $em->createQuery($dql)->getResult();

    /*Aplico la paginacion con:
          * la consulta ejecutada,
          * empieza desde la pagina 1,
          * y muestro 10 partidos por pagina
    */

    $logInUser = $this->get('security.token_storage')->getToken()->getUser();

    return $this->render('IAWTorneoBundle:Partido:index.html.twig', array('partidos' => $partidos));

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

      $puntajeLocal = $em->getRepository('IAWParticipanteBundle:Puntaje')->createQueryBuilder('p')
                      ->where('p.equipo = :equipo')
                      ->setParameter('equipo',$equipoLocal)
                      ->getQuery()
                      ->getResult();
      $puntajeVisitante = $em->getRepository('IAWParticipanteBundle:Puntaje')->createQueryBuilder('p')
                      ->where('p.equipo = :equipo')
                      ->setParameter('equipo',$equipoVisitante)
                      ->getQuery()
                      ->getResult();

     if($golesEquipoLocal == $golesEquipoVisitante){

        $puntos = $puntajeLocal[0]->getPuntos();
        $pj = $puntajeLocal[0]->getPj();
        $pe = $puntajeLocal[0]->getPe();
        $gf = $puntajeLocal[0]->getGf();
        $gc = $puntajeLocal[0]->getGc();

        $puntajeLocal[0]->setPuntos($puntos + 1);
        $puntajeLocal[0]->setPj($pj + 1);
        $puntajeLocal[0]->setPe($pe + 1);
        $puntajeLocal[0]->setGf($gf + $golesEquipoLocal);
        $puntajeLocal[0]->setGc($gc + $golesEquipoVisitante);

        $gf = $puntajeLocal[0]->getGf();
        $gc = $puntajeLocal[0]->getGc();

        $puntajeLocal[0]->setDg($gf- $gc);

        $puntos = $puntajeVisitante[0]->getPuntos();
        $pj = $puntajeVisitante[0]->getPj();
        $pe = $puntajeVisitante[0]->getPe();
        $gf = $puntajeVisitante[0]->getGf();
        $gc = $puntajeVisitante[0]->getGc();

        $puntajeVisitante[0]->setPuntos($puntos + 1);
        $puntajeVisitante[0]->setPj($pj + 1);
        $puntajeVisitante[0]->setPe($pe + 1);
        $puntajeVisitante[0]->setGf($gf + $golesEquipoVisitante);
        $puntajeVisitante[0]->setGc($gc + $golesEquipoLocal);

        $gf = $puntajeVisitante[0]->getGf();
        $gc = $puntajeVisitante[0]->getGc();

        $puntajeVisitante[0]->setDg($gf- $gc);

        $em->flush();
      }
      if($golesEquipoLocal > $golesEquipoVisitante){

        $puntos = $puntajeLocal[0]->getPuntos();
        $pj = $puntajeLocal[0]->getPj();
        $pg = $puntajeLocal[0]->getPg();
        $gf = $puntajeLocal[0]->getGf();
        $gc = $puntajeLocal[0]->getGc();

        $puntajeLocal[0]->setPuntos($puntos + 3);
        $puntajeLocal[0]->setPj($pj + 1);
        $puntajeLocal[0]->setPg($pg + 1);
        $puntajeLocal[0]->setGf($gf + $golesEquipoLocal);
        $puntajeLocal[0]->setGc($gc + $golesEquipoVisitante);

        $gf = $puntajeLocal[0]->getGf();
        $gc = $puntajeLocal[0]->getGc();

        $puntajeLocal[0]->setDg($gf- $gc);

        $pj = $puntajeVisitante[0]->getPj();
        $pp = $puntajeVisitante[0]->getPp();
        $gf = $puntajeVisitante[0]->getGf();
        $gc = $puntajeVisitante[0]->getGc();
        $Dg = $puntajeVisitante[0]->getDg();

        $puntajeVisitante[0]->setPj($pj + 1);
        $puntajeVisitante[0]->setPp($pp + 1);
        $puntajeVisitante[0]->setGf($gf + $golesEquipoVisitante);
        $puntajeVisitante[0]->setGc($gc + $golesEquipoLocal);

        $gf = $puntajeVisitante[0]->getGf();
        $gc = $puntajeVisitante[0]->getGc();

        $puntajeVisitante[0]->setDg($gf- $gc);

        $em->flush();


      }
      if($golesEquipoLocal < $golesEquipoVisitante){

        $pj = $puntajeLocal[0]->getPj();
        $pp = $puntajeLocal[0]->getPp();
        $gf = $puntajeLocal[0]->getGf();
        $gc = $puntajeLocal[0]->getGc();

        $puntajeLocal[0]->setPj($pj + 1);
        $puntajeLocal[0]->setPp($pp + 1);
        $puntajeLocal[0]->setGf($gf + $golesEquipoLocal);
        $puntajeLocal[0]->setGc($gc + $golesEquipoVisitante);

        $gf = $puntajeLocal[0]->getGf();
        $gc = $puntajeLocal[0]->getGc();

        $puntajeLocal[0]->setDg($gf- $gc);

        $puntos = $puntajeVisitante[0]->getPuntos();
        $pj = $puntajeVisitante[0]->getPj();
        $pg = $puntajeVisitante[0]->getPg();
        $gf = $puntajeVisitante[0]->getGf();
        $gc = $puntajeVisitante[0]->getGc();

        $puntajeVisitante[0]->setPuntos($puntos + 3);
        $puntajeVisitante[0]->setPj($pj + 1);
        $puntajeVisitante[0]->setPg($pg + 1);
        $puntajeVisitante[0]->setGf($gf + $golesEquipoVisitante);
        $puntajeVisitante[0]->setGc($gc + $golesEquipoLocal);

        $gf = $puntajeVisitante[0]->getGf();
        $gc = $puntajeVisitante[0]->getGc();

        $puntajeVisitante[0]->setDg($gf- $gc);

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
    return $this->render('IAWTorneoBundle:Partido:edit.html.twig', array('logInUser' => $logInUser,'partido' => $partido, 'form' => $form->createView()));
  }



  }
