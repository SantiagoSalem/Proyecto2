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


class PartidoController extends Controller
{

  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    //El primer participante que aparece en la lista es el ultimo insertado.
    $dql = "SELECT p.id, p.equipoLocal, p.equipoVisitante, pp.imagePath as imagenEL, ppp.imagePath as imagenEV
            FROM IAWTorneoBundle:Partido p, IAWParticipanteBundle:Participante pp, IAWParticipanteBundle:Participante ppp
            WHERE p.equipoLocal = pp.name  AND p.equipoVisitante = ppp.name"; //Me tira error al ordenar por fecha...

    //Ejecuto la consulta
    $partidos = $em->createQuery($dql);


    /*Aplico la paginacion con:
          * la consulta ejecutada,
          * empieza desde la pagina 1,
          * y muestro 10 partidos por pagina
    */

    $logInUser = $this->get('security.token_storage')->getToken()->getUser();

    return $this->render('IAWTorneoBundle:Partido:index.html.twig', array('logInUser' => $logInUser,'partidos' => $partidos));



  }


}
