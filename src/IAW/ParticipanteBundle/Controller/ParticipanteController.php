<?php

namespace IAW\ParticipanteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormError;
use IAW\ParticipanteBundle\Entity\Participante;
use IAW\ParticipanteBundle\Form\ParticipanteType;
use Symfony\Component\HttpFoundation\File\File;

class ParticipanteController extends Controller
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

      return $this->render('IAWParticipanteBundle:Participante:index.html.twig', array('pagination' => $pagination,
          'delete_form_ajax' => $deleteFormAjax->createView()));
    }

    /*****************************
    **Inicio agregar participante**
    *******************************/

    public function addAction(){

      $participante = new Participante();
      $form = $this->createCreateForm($participante);

      return $this->render('IAWParticipanteBundle:Participante:add.html.twig', array('form' => $form->createView()));

    }

    private function createCreateForm(Participante $entidad){

      /*Creo el formulario con:
          * El formulario,
          * la entidad,
          * opciones:
              * generar ruta participante/create
              * metodo POST
      */
      $form = $this->createForm(new ParticipanteType(), $entidad, array(
          'action' => $this->generateUrl('iaw_participante_create'),
          'method' => 'POST'
      ));

      return $form;
    }

    public function createAction(Request $request){

      //Obtengo el formulario procesandolo con el objeto request
      $participante = new Participante();
      $form = $this->createCreateForm($participante);
      $form->handleRequest($request);

      if($form->isValid()){

        //Obtengo la imagen que se ingreso en el formulario
        $file = $form["imagePath"]->getData();
        //Obtengo la extension de la imagen
        $ext = $file->guessExtension();
        //Nombre que va a recibir el archivo
        $file_name = time().".".$ext;
        //Guardo la imagen en uploads/
        $file->move("uploads",$file_name);

        $participante->setImagePath($file_name);

        //Guardo en la base de datos
        $em = $this->getDoctrine()->getManager();
        $em->persist($participante);
        $em->flush();

        //Genero el mensaje para mostrar
        $this->addFlash(
            'mensajeAddParticipante',
            'Nuevo participante creado correctamente'
        );

        return $this->redirectToRoute('iaw_participante_index');
      }
      //En caso de algun problema, renderizo el formulario
      return $this->render('IAWParticipanteBundle:Participante:add.html.twig', array('form' => $form->createView()));
    }

    /*****************************
    ****Fin agregar participante***
    *******************************/

    /*****************************
    ****Inicio ver participante****
    *******************************/

    public function viewAction($id){

      $repository = $this->getDoctrine()->getRepository('IAWParticipanteBundle:Participante');

      //Obtengo el participante con el id indicado
      $participante = $repository->find($id);

      //Verifico si existe el participante con el id indicado
      if(!$participante){
        throw $this->createNotFoundException('Participante no encontrado');
      }

      $deleteForm = $this->createCustomForm($participante->getId(), 'DELETE', 'iaw_participante_delete');

      return $this->render('IAWParticipanteBundle:Participante:view.html.twig',array('participante' => $participante,'delete_form' => $deleteForm->createView()));

    }

    /*****************************
    *****Fin ver participante****
    *******************************/


    /*****************************
    **Inicio eliminar participante*
    *******************************/

    public function deleteAction(Request $request, $id){

      $em = $this->getDoctrine()->getManager();
      //Obtengo el participante con el id indicado
      $participante = $em->getRepository('IAWParticipanteBundle:Participante')->find($id);

      //Verifico si existe el participante con el id indicado
      if(!$participante){
        throw $this->createNotFoundException('Participante no encontrado');
      }

      //Creo el formulario para eliminar
      $form = $this->createCustomForm($participante->getId(), 'DELETE', 'iaw_participante_delete');
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){

        //Verifico si se trata de una llamada ajax
        if($request->isXMLHttpRequest()){
          $file_name = $participante->getImagePath();
          if($file_name != ""){
            //Borro la imagen del equipo (escudo) de la carpeta uploads/
            $file_path = 'uploads'."/".$file_name;
            unlink($file_path);
          }
          $em->remove($participante);
          $em->flush();
          return new Response(
            json_encode(array(
              'message' => 'Participante eliminado correctamente')),
              200,
              array('Content-Type' => 'application/json')
          );
        }
        //Fin  llamada ajax

        $em->remove($participante);
        $em->flush();

        $this->addFlash(
            'mensajeAddParticipante',
            'Participante eliminado correctamente'
        );

        return $this->redirectToRoute('iaw_participante_index');
      }
    }

    /*****************************
    ***Fin eliminar participante***
    *******************************/


    /*****************************
    **Inicio editar participante***
    *******************************/

    public function editAction($id){

      $em = $this->getDoctrine()->getManager();
      $participante = $em->getRepository('IAWParticipanteBundle:Participante')->find($id);

      //Verifico si existe el participante con el id indicado
      if(!$participante){
        throw $this->createNotFoundException('Participante no encontrado');
      }

      $form = $this->createEditForm($participante);

      return $this->render('IAWParticipanteBundle:Participante:edit.html.twig', array('participante' => $participante,'form' => $form->createView()));
    }

    private function createEditForm(Participante $entidad){

      /*Creo el formulario con:
          * El formulario,
          * la entidad,
          * opciones:
              * generar ruta participante/update/{id}
              * metodo PUT
      */
      $form = $this->createForm(new ParticipanteType(), $entidad, array(
            'action' => $this->generateUrl('iaw_participante_update', array('id' => $entidad->getId())),
            'method' => 'PUT'));
      return $form;
    }

    public function updateAction($id, Request $request){

      $em = $this->getDoctrine()->getManager();

      //Obtengo el participante con el id indicado
      $participante = $em->getRepository('IAWParticipanteBundle:Participante')->find($id);

      //Verifico si existe el participante con el id indicado
      if(!$participante){
        throw $this->createNotFoundException('Participante no encontrado');
      }

      //Obtengo el formulario procesandolo con el objeto request
      $form = $this->createEditForm($participante);
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){

        //Guardo en la base de datos
        $em->flush();

        //Genero el mensaje para mostrar
        $this->addFlash('mensajeAddParticipante', "Participante actualizado correctamente");

        return $this->redirectToRoute('iaw_participante_edit', array('id' => $participante->getId()));
      }
      //En caso de algun problema, renderizo el formulario
      return $this->render('IAWParticipanteBundle:Participante:edit.html.twig', array('participante', 'form' => $form->createView()));
    }

    /*****************************
    ****Fin editar participante***
    *******************************/

    private function createCustomForm($id, $method, $route){

      return $this->createFormBuilder()
          ->setAction($this->generateUrl($route, array('id' => $id)))
          ->setMethod($method)
          ->getForm();
    }



}
