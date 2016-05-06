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
      $rta = $this->getDoctrine()->getManager();

    //  $participantes = $rta->getRepository('IAWParticipanteBundle:Participante')->findAll();

      $dql = "SELECT p FROM IAWParticipanteBundle:Participante p ORDER BY p.id DESC";
      $participantes = $rta->createQuery($dql);

      $paginator = $this->get('knp_paginator');
      $pagination = $paginator->paginate(
            $participantes, $request->query->getInt('page',1),
            5
      );

    /*  $res = '.Lista de usuarios: <br />';

      foreach ($ws as $user) {
        $res .= 'Usuario: ' . $user->getUsername() . ' - Password:' . $user->getPassword().  '<br />';
      }

    */

      return $this->render('IAWParticipanteBundle:Participante:index.html.twig', array('pagination' => $pagination));
    }

    public function viewAction($id){

      $repository = $this->getDoctrine()->getRepository('IAWParticipanteBundle:Participante');

      $participante = $repository->find($id);

      if(!$participante){
        throw $this->createNotFoundException('Participante no encontrado');
      }

    //  $deleteForm = $this->createDeleteForm($participante);

      return $this->render('IAWParticipanteBundle:Participante:view.html.twig',array('participante' => $participante));

    }


    public function addAction(){

      $participante = new Participante();
      $form = $this->createCreateForm($participante);

      return $this->render('IAWParticipanteBundle:Participante:add.html.twig', array('form' => $form->createView()));

    }

    private function createCreateForm(Participante $entity){
      $form = $this->createForm(new ParticipanteType(), $entity, array(
          'action' => $this->generateUrl('iaw_participante_create'),
          'method' => 'POST'
      ));

      return $form;
    }

    public function createAction(Request $request){

      $participante = new Participante();
      $form = $this->createCreateForm($participante);
      $form->handleRequest($request);

      if($form->isValid()){

        $file = $form["imagePath"]->getData();
        $ext = $file->guessExtension();
        $file_name = time().".".$ext;
        $file->move("uploads",$file_name);

        $participante->setImagePath($file_name);


        $this->addFlash(
            'mensajeAddParticipante',
            'Nuevo participante creado correctamente'
        );

        $rta = $this->getDoctrine()->getManager();
        $rta->persist($participante);
        $rta->flush();

        return $this->redirectToRoute('iaw_participante_index');
      }
      return $this->render('IAWParticipanteBundle:Participante:add.html.twig', array('form' => $form->createView()));
    }

    public function editAction($id){

      $rta = $this->getDoctrine()->getManager();
      $participante = $rta->getRepository('IAWParticipanteBundle:Participante')->find($id);

      //Verifico si existe el participante con el id indicado
      if(!$participante){
        throw $this->createNotFoundException('Participante no encontrado');
      }

      $form = $this->createEditForm($participante);

      return $this->render('IAWParticipanteBundle:Participante:edit.html.twig', array('participante' => $participante,'form' => $form->createView()));
    }

    private function createEditForm(Participante $entity){

      $form = $this->createForm(new ParticipanteType(), $entity, array('action' => $this->generateUrl('iaw_participante_update', array('id' => $entity->getId())), 'method' => 'PUT'));
      return $form;
    }

    public function updateAction($id, Request $request){

      $rta = $this->getDoctrine()->getManager();
      $participante = $rta->getRepository('IAWParticipanteBundle:Participante')->find($id);

      //Verifico si existe el usuario con el id indicado
      if(!$participante){
        throw $this->createNotFoundException('Participante no encontrado');
      }

      $form = $this->createEditForm($participante);
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){
        $rta->flush();
        $this->addFlash('mensajeAddParticipante', "Participante actualizado correctamente");
        return $this->redirectToRoute('iaw_participante_edit', array('id' => $participante->getId()));
      }

      return $this->render('IAWParticipanteBundle:Participante:edit.html.twig', array('participante', 'form' => $form->createView()));


    }




}
