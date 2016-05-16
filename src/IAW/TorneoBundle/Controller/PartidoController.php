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

      $dql = "SELECT p.id, p.equipoLocal, p.equipoVisitante, f.nroFecha as nroFecha, DATE_FORMAT(f.date, '%d-%m-%Y') as fechaFormat,
      CASE WHEN MONTH(f.date) = 1 THEN 'enero'
           WHEN MONTH(f.date) = 2 THEN 'febrero'
           WHEN MONTH(f.date) = 3 THEN 'marzo'
           WHEN MONTH(f.date) = 4 THEN 'abril'
           WHEN MONTH(f.date) = 5 THEN 'mayo'
           WHEN MONTH(f.date) = 6 THEN 'junio'
           WHEN MONTH(f.date) = 7 THEN 'julio'
           WHEN MONTH(f.date) = 8 THEN 'agosto'
           WHEN MONTH(f.date) = 9 THEN 'septiembre'
           WHEN MONTH(f.date) = 10 THEN 'octubre'
           WHEN MONTH(f.date) = 11 THEN 'noviembre'
           WHEN MONTH(f.date) = 12 THEN 'diciembre'
           ELSE 'No es un mes'
      END as mes,
      CASE WHEN WEEKDAY(f.date) = 0 THEN 'Lunes'
           WHEN WEEKDAY(f.date) = 1 THEN 'Martes'
           WHEN WEEKDAY(f.date) = 2 THEN 'Miercoles'
           WHEN WEEKDAY(f.date) = 3 THEN 'Jueves'
           WHEN WEEKDAY(f.date) = 4 THEN 'Viernes'
           WHEN WEEKDAY(f.date) = 5 THEN 'Sabado'
           WHEN WEEKDAY(f.date) = 6 THEN 'Domingo'
           ELSE 'No es un dia'
      END AS dia, DAY(f.date) as nroDia, pp.imagePath as imagenEL, ppp.imagePath as imagenEV, p.editor
              FROM IAWTorneoBundle:Partido p, IAWParticipanteBundle:Participante pp, IAWParticipanteBundle:Participante ppp, IAWTorneoBundle:FechaTorneo f
              WHERE p.equipoLocal = pp.name  AND p.equipoVisitante = ppp.name AND IDENTITY(p.fecha) = f.id
              ORDER BY p.fecha ASC";
    }
    else{
    //El primer participante que aparece en la lista es el ultimo insertado.
    $dql = "SELECT p.id, p.equipoLocal, p.equipoVisitante, f.nroFecha as nroFecha, DATE_FORMAT(f.date, '%d-%m-%Y') as fechaFormat,
    CASE WHEN MONTH(f.date) = 1 THEN 'enero'
         WHEN MONTH(f.date) = 2 THEN 'febrero'
         WHEN MONTH(f.date) = 3 THEN 'marzo'
         WHEN MONTH(f.date) = 4 THEN 'abril'
         WHEN MONTH(f.date) = 5 THEN 'mayo'
         WHEN MONTH(f.date) = 6 THEN 'junio'
         WHEN MONTH(f.date) = 7 THEN 'julio'
         WHEN MONTH(f.date) = 8 THEN 'agosto'
         WHEN MONTH(f.date) = 9 THEN 'septiembre'
         WHEN MONTH(f.date) = 10 THEN 'octubre'
         WHEN MONTH(f.date) = 11 THEN 'noviembre'
         WHEN MONTH(f.date) = 12 THEN 'diciembre'
         ELSE 'No es un mes'
    END as mes,
    CASE WHEN WEEKDAY(f.date) = 0 THEN 'Lunes'
         WHEN WEEKDAY(f.date) = 1 THEN 'Martes'
         WHEN WEEKDAY(f.date) = 2 THEN 'Miercoles'
         WHEN WEEKDAY(f.date) = 3 THEN 'Jueves'
         WHEN WEEKDAY(f.date) = 4 THEN 'Viernes'
         WHEN WEEKDAY(f.date) = 5 THEN 'Sabado'
         WHEN WEEKDAY(f.date) = 6 THEN 'Domingo'
         ELSE 'No es un dia'
    END AS dia, DAY(f.date) as nroDia, pp.imagePath as imagenEL, ppp.imagePath as imagenEV
            FROM IAWTorneoBundle:Partido p, IAWParticipanteBundle:Participante pp, IAWParticipanteBundle:Participante ppp, IAWTorneoBundle:FechaTorneo f
            WHERE p.equipoLocal = pp.name  AND p.equipoVisitante = ppp.name AND p.golesEquipoLocal IS NULL AND p.editor = '$logInUser' AND IDENTITY(p.fecha) = f.id
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
    $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";
    $cantTorneo = $em->createQuery($dql)->getResult();

    return $this->render('IAWTorneoBundle:Partido:index.html.twig', array('logInUser' => $logInUser,'cantTorneo' => $cantTorneo,'partidos' => $partidos));

  }

  public function verPartidosAction()
  {

    $em = $this->getDoctrine()->getManager();

    //El primer participante que aparece en la lista es el ultimo insertado.
    $dql = "SELECT p.id, p.equipoLocal, p.equipoVisitante, f.nroFecha as nroFecha, DATE_FORMAT(f.date, '%d-%m-%Y') as fechaFormat,
    CASE WHEN MONTH(f.date) = 1 THEN 'enero'
         WHEN MONTH(f.date) = 2 THEN 'febrero'
         WHEN MONTH(f.date) = 3 THEN 'marzo'
         WHEN MONTH(f.date) = 4 THEN 'abril'
         WHEN MONTH(f.date) = 5 THEN 'mayo'
         WHEN MONTH(f.date) = 6 THEN 'junio'
         WHEN MONTH(f.date) = 7 THEN 'julio'
         WHEN MONTH(f.date) = 8 THEN 'agosto'
         WHEN MONTH(f.date) = 9 THEN 'septiembre'
         WHEN MONTH(f.date) = 10 THEN 'octubre'
         WHEN MONTH(f.date) = 11 THEN 'noviembre'
         WHEN MONTH(f.date) = 12 THEN 'diciembre'
         ELSE 'No es un mes'
    END as mes,
    CASE WHEN WEEKDAY(f.date) = 0 THEN 'Lunes'
         WHEN WEEKDAY(f.date) = 1 THEN 'Martes'
         WHEN WEEKDAY(f.date) = 2 THEN 'Miercoles'
         WHEN WEEKDAY(f.date) = 3 THEN 'Jueves'
         WHEN WEEKDAY(f.date) = 4 THEN 'Viernes'
         WHEN WEEKDAY(f.date) = 5 THEN 'Sabado'
         WHEN WEEKDAY(f.date) = 6 THEN 'Domingo'
         ELSE 'No es un dia'
    END AS dia, DAY(f.date) as nroDia,pp.imagePath as imagenEL, ppp.imagePath as imagenEV
            FROM IAWTorneoBundle:Partido p, IAWParticipanteBundle:Participante pp, IAWParticipanteBundle:Participante ppp, IAWTorneoBundle:FechaTorneo f
            WHERE p.equipoLocal = pp.name  AND p.equipoVisitante = ppp.name AND IDENTITY(p.fecha) = f.id
            ORDER BY p.fecha ASC"; //Me tira error al ordenar por fecha...

    //Ejecuto la consulta
    $partidos = $em->createQuery($dql)->getResult();

    /*Aplico la paginacion con:
          * la consulta ejecutada,
          * empieza desde la pagina 1,
          * y muestro 10 partidos por pagina
    */

    $logInUser = $this->get('security.token_storage')->getToken()->getUser();
    $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";
    $cantTorneo = $em->createQuery($dql)->getResult();

    return $this->render('IAWTorneoBundle:Partido:index.html.twig', array('cantTorneo' => $cantTorneo,'partidos' => $partidos));

  }

  public function resultadosPartidosAction()
  {

    $em = $this->getDoctrine()->getManager();

    //El primer participante que aparece en la lista es el ultimo insertado.
    $dql = "SELECT p.id, p.equipoLocal, p.equipoVisitante, p.golesEquipoLocal, p.golesEquipoVisitante, f.nroFecha as nroFecha, DATE_FORMAT(f.date, '%d-%m-%Y') as fechaFormat,
    CASE WHEN MONTH(f.date) = 1 THEN 'enero'
         WHEN MONTH(f.date) = 2 THEN 'febrero'
         WHEN MONTH(f.date) = 3 THEN 'marzo'
         WHEN MONTH(f.date) = 4 THEN 'abril'
         WHEN MONTH(f.date) = 5 THEN 'mayo'
         WHEN MONTH(f.date) = 6 THEN 'junio'
         WHEN MONTH(f.date) = 7 THEN 'julio'
         WHEN MONTH(f.date) = 8 THEN 'agosto'
         WHEN MONTH(f.date) = 9 THEN 'septiembre'
         WHEN MONTH(f.date) = 10 THEN 'octubre'
         WHEN MONTH(f.date) = 11 THEN 'noviembre'
         WHEN MONTH(f.date) = 12 THEN 'diciembre'
         ELSE 'No es un mes'
    END as mes,
    CASE WHEN WEEKDAY(f.date) = 0 THEN 'Lunes'
         WHEN WEEKDAY(f.date) = 1 THEN 'Martes'
         WHEN WEEKDAY(f.date) = 2 THEN 'Miercoles'
         WHEN WEEKDAY(f.date) = 3 THEN 'Jueves'
         WHEN WEEKDAY(f.date) = 4 THEN 'Viernes'
         WHEN WEEKDAY(f.date) = 5 THEN 'Sabado'
         WHEN WEEKDAY(f.date) = 6 THEN 'Domingo'
         ELSE 'No es un dia'
    END AS dia, DAY(f.date) as nroDia, pp.imagePath as imagenEL, ppp.imagePath as imagenEV
            FROM IAWTorneoBundle:Partido p, IAWParticipanteBundle:Participante pp, IAWParticipanteBundle:Participante ppp, IAWTorneoBundle:FechaTorneo f
            WHERE p.equipoLocal = pp.name  AND p.equipoVisitante = ppp.name AND p.golesEquipoLocal IS NOT NULL AND IDENTITY(p.fecha) = f.id
            ORDER BY p.fecha ASC"; //Me tira error al ordenar por fecha...

    //Ejecuto la consulta
    $partidos = $em->createQuery($dql)->getResult();

    /*Aplico la paginacion con:
          * la consulta ejecutada,
          * empieza desde la pagina 1,
          * y muestro 10 partidos por pagina
    */

    $logInUser = $this->get('security.token_storage')->getToken()->getUser();
    $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";
    $cantTorneo = $em->createQuery($dql)->getResult();

    return $this->render('IAWTorneoBundle:Partido:resultados.html.twig', array('cantTorneo' => $cantTorneo,'partidos' => $partidos));

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
    $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";
    $cantTorneo = $em->createQuery($dql)->getResult();

    return $this->render('IAWTorneoBundle:Partido:edit.html.twig', array('logInUser' => $logInUser,'cantTorneo' => $cantTorneo,'partido' => $partido,'form' => $form->createView()));
  }

  private function createEditForm(Partido $entidad){

    /*Creo el formulario con:
        * El formulario,
        * la entidad,
        * opciones:
            * generar ruta user/update/{id}
            * metodo PUT
    */
    $em = $this->getDoctrine()->getManager();
    $sac = $this->get('security.authorization_checker');
    $form = $this->createForm(new PartidoType($em,$sac), $entidad, array(
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



      if($golesEquipoLocal != NULL && $golesEquipoVisitante != NULL ) {

          if($this->get('security.authorization_checker')->isGranted('ROLE_EDITOR') === true){
              //Datos local
              $puntosL = $puntajeLocal[0]->getPuntos();
              $pjL = $puntajeLocal[0]->getPj();
              $pgL = $puntajeLocal[0]->getPg();
              $peL = $puntajeLocal[0]->getPe();
              $ppL = $puntajeLocal[0]->getPp();
              $gfL = $puntajeLocal[0]->getGf();
              $gcL = $puntajeLocal[0]->getGc();

              //Modifijo pj, gf, gc del local
              $puntajeLocal[0]->setPj($pjL + 1);
              $puntajeLocal[0]->setGf($gfL + $golesEquipoLocal);
              $puntajeLocal[0]->setGc($gcL + $golesEquipoVisitante);

              $gfL = $puntajeLocal[0]->getGf();
              $gcL = $puntajeLocal[0]->getGc();

              //Modifico dg del local
              $puntajeLocal[0]->setDg($gfL- $gcL);

              //Datos del visitante
              $puntosV = $puntajeVisitante[0]->getPuntos();
              $pjV = $puntajeVisitante[0]->getPj();
              $pgV = $puntajeVisitante[0]->getPg();
              $peV = $puntajeVisitante[0]->getPe();
              $ppV = $puntajeVisitante[0]->getPp();
              $gfV = $puntajeVisitante[0]->getGf();
              $gcV = $puntajeVisitante[0]->getGc();

              //Modifijo pj, gf, gc del visitante
              $puntajeVisitante[0]->setPj($pjV + 1);
              $puntajeVisitante[0]->setGf($gfV + $golesEquipoVisitante);
              $puntajeVisitante[0]->setGc($gcV + $golesEquipoLocal);

              $gfV = $puntajeVisitante[0]->getGf();
              $gcV = $puntajeVisitante[0]->getGc();

              //Modifico dg del visitante
              $puntajeVisitante[0]->setDg($gfV- $gcV);


              if($golesEquipoLocal == $golesEquipoVisitante){

                  //Sumo 1 punto
                  $puntajeLocal[0]->setPuntos($puntosL + 1);
                  //Sumo 1 partido empatado
                  $puntajeLocal[0]->setPe($peL + 1);



                  //Sumo 1 punto
                  $puntajeVisitante[0]->setPuntos($puntosV + 1);
                  //Sumo 1 partido empatado
                  $puntajeVisitante[0]->setPe($peV + 1);

              }
              if($golesEquipoLocal > $golesEquipoVisitante){

                  //Sumo 3 puntos
                  $puntajeLocal[0]->setPuntos($puntosL + 3);
                  //Sumo 1 partido ganado
                  $puntajeLocal[0]->setPg($pgL + 1);

                  //Sumo 1 partido perdido
                  $puntajeVisitante[0]->setPp($ppV + 1);

              }
              if($golesEquipoLocal < $golesEquipoVisitante){

                  //Sumo 1 partido perdido
                  $puntajeLocal[0]->setPp($ppL + 1);

                  //Sumo 3 puntos
                  $puntajeVisitante[0]->setPuntos($puntosV + 3);
                  //Sumo 1 partido ganado
                  $puntajeVisitante[0]->setPg($pgV + 1);

              }
            }
            else{

                $partidoActual = $em->getRepository('IAWTorneoBundle:Partido')->createQueryBuilder('p')
                              ->where('p.equipoLocal = :equipoLocal')
                              ->andwhere('p.equipoVisitante = :equipoVisitante')
                              ->setParameter('equipoLocal',$equipoLocal)
                              ->setParameter('equipoVisitante',$equipoVisitante)
                              ->getQuery()
                              ->getResult();

                $golesEquipoLocalAntes = $partidoActual[0]->getGolesEquipoLocal();
                $golesEquipoVisitanteAntes = $partidoActual[0]->getGolesEquipoVisitante();

                //Tengo que corregir gfL, gcL y gdL
                $gfL = $puntajeLocal[0]->getGf();
                $gcL = $puntajeLocal[0]->getGc();

                //Modifico el gfL y gcL
                $puntajeLocal[0]->setGf($gfL - $golesEquipoLocalAntes + $golesEquipoLocal);
                $puntajeLocal[0]->setGc($gcL - $golesEquipoVisitanteAntes + $golesEquipoVisitante);

                $gfL = $puntajeLocal[0]->getGf();
                $gcL = $puntajeLocal[0]->getGc();

                //Modifico df del local
                $puntajeLocal[0]->setDg($gfL - $gcL);


                //Tengo que corregir gfV, gcV y gdV
                $gfV = $puntajeVisitante[0]->getGf();
                $gcV = $puntajeVisitante[0]->getGc();

                //Modifico el gfV y gcV
                $puntajeVisitante[0]->setGf($gfV - $golesEquipoVisitanteAntes + $golesEquipoVisitante);
                $puntajeVisitante[0]->setGc($gcV - $golesEquipoLocalAntes + $golesEquipoLocal);

                $gfV = $puntajeVisitante[0]->getGf();
                $gcV = $puntajeVisitante[0]->getGc();

                //Modifico df del visitante
                $puntajeVisitante[0]->setDg($gfV - $gcV);
              }
      }

      //Guardo en la base de datos
      $em->flush();

      //Genero el mensaje para mostrar
      $this->addFlash('mensaje', "Partido actualizado correctamente");

      return $this->redirectToRoute('iaw_partido_index');
    }
    $logInUser = $this->get('security.token_storage')->getToken()->getUser();
    $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";
    $cantTorneo = $em->createQuery($dql)->getResult();
    //En caso de algun problema, renderizo el formulario
    return $this->render('IAWTorneoBundle:Partido:edit.html.twig', array('logInUser' => $logInUser,'cantTorneo' => $cantTorneo,'partido' => $partido, 'form' => $form->createView()));
  }



  }
