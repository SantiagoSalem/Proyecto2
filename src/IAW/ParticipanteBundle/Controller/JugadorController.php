<?php

namespace IAW\ParticipanteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormError;
use IAW\ParticipanteBundle\Entity\Participante;
use IAW\ParticipanteBundle\Entity\Jugador;
use IAW\ParticipanteBundle\Form\ParticipanteType;
use IAW\ParticipanteBundle\Form\JugadorType;
use Symfony\Component\HttpFoundation\File\File;

class JugadorController extends Controller
{
    public function indexAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();

      //El primer participante que aparece en la lista es el ultimo insertado.
      $dql = "SELECT p FROM IAWParticipanteBundle:Participante p ORDER BY p.id DESC";

      //Ejecuto la consulta
      $participantes = $em->createQuery($dql);

      /*Aplico la paginacion con:
            * la consulta ejecutada,
            * empieza desde la pagina 1,
            * y muestro 5 participantes por pagina
      */
      $paginator = $this->get('knp_paginator');
      $pagination = $paginator->paginate(
            $participantes, $request->query->getInt('page',1),
            5
      );

      $deleteFormAjax = $this->createCustomForm(':PARTICIPANTE_ID', 'DELETE', 'iaw_participante_delete');

      $logInUser = $this->get('security.token_storage')->getToken()->getUser();
      $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";
      $cantTorneo = $em->createQuery($dql)->getResult();

      return $this->render('IAWParticipanteBundle:Participante:index.html.twig', array('logInUser' => $logInUser,'cantTorneo' => $cantTorneo,'pagination' => $pagination,
          'delete_form_ajax' => $deleteFormAjax->createView()));
    }

    /*****************************
    **Inicio agregar participante**
    *******************************/

    public function addAction($participanteID){

      $jugador = new Jugador();

      $em = $this->getDoctrine()->getManager();
      $participante = $em->getRepository('IAWParticipanteBundle:Participante')->find($participanteID);

      //Verifico si existe el participante con el id indicado
      if(!$participante){
        throw $this->createNotFoundException('Participante no encontrado');
      }


      $form = $this->createCreateForm($participanteID, $jugador);

      $logInUser = $this->get('security.token_storage')->getToken()->getUser();
      $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";
      $cantTorneo = $em->createQuery($dql)->getResult();



      return $this->render('IAWParticipanteBundle:Jugador:add.html.twig', array('logInUser' => $logInUser,'cantTorneo' => $cantTorneo,'form' => $form->createView()));

    }

    private function createCreateForm($participanteID, Jugador $entidad){

      /*Creo el formulario con:
          * El formulario,
          * la entidad,
          * opciones:
              * generar ruta participante/create
              * metodo POST
      */
      $form = $this->createForm(new JugadorType(), $entidad, array(
          'action' => $this->generateUrl('iaw_jugador_create', array('participanteID' => $participanteID)),
          'method' => 'POST'
      ));

      return $form;
    }

    public function createAction($participanteID, Request $request){

      //Obtengo el formulario procesandolo con el objeto request
      $jugador = new Jugador();
      $form = $this->createCreateForm($participanteID,$jugador);
      $form->handleRequest($request);

      if($form->isValid()){

        //Obtengo la imagen que se ingreso en el formulario
        $file = $form["imagen"]->getData();
        //Obtengo la extension de la imagen
        $ext = $file->guessExtension();
        //Nombre que va a recibir el archivo
        $file_name = time().".".$ext;
        //Guardo la imagen en uploads/
        $file->move("uploads",$file_name);

        $jugador->setImagen($file_name);

        $jugador->setParticipanteID($participanteID);


        //Guardo en la base de datos
        $em = $this->getDoctrine()->getManager();
        $em->persist($jugador);
        $em->flush();


        $participante = $em->getRepository('IAWParticipanteBundle:Participante')->createQueryBuilder('p')
                        ->where('p.id = :id')
                        ->setParameter('id',$participanteID)
                        ->getQuery()
                        ->getResult();


        $participante[0]->setMembers($participante[0]->getMembers() + 1);
        $em->flush();

        //Genero el mensaje para mostrar
        $this->addFlash(
            'mensajeAddParticipante',
            'Nuevo jugador creado correctamente'
        );

        return $this->redirectToRoute('iaw_participante_index');
      }
      $em = $this->getDoctrine()->getManager();
      $logInUser = $this->get('security.token_storage')->getToken()->getUser();
      $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";
      $cantTorneo = $em->createQuery($dql)->getResult();

      //En caso de algun problema, renderizo el formulario
      return $this->render('IAWParticipanteBundle:Jugador:add.html.twig', array('logInUser' => $logInUser,'cantTorneo' => $cantTorneo,'form' => $form->createView()));
    }
  }
