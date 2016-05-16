<?php

namespace IAW\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use IAW\UserBundle\Entity\User;
use IAW\UserBundle\Form\UserType;

class UserController extends Controller
{
    public function indexAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      //El primer usuario que aparece en la lista es el ultimo insertado.
      $dql = "SELECT u FROM IAWUserBundle:User u ORDER BY u.id DESC";
      //Ejecuto la consulta
      $users = $em->createQuery($dql);

      $paginator = $this->get('knp_paginator');

      //Obtengo el usuario logueado
      $logInUser = $this->get('security.token_storage')->getToken()->getUser();

      $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";
      $cantTorneo = $em->createQuery($dql)->getResult();


      /*Aplico la paginacion con:
            * la consulta ejecutada,
            * empieza desde la pagina 1,
            * y muestro 5 usuarios por pagina
      */
      $pagination = $paginator->paginate(
            $users, $request->query->getInt('page',1),
            5
      );

      $deleteFormAjax = $this->createCustomForm(':USER_ID', 'DELETE', 'iaw_user_delete');

      return $this->render('IAWUserBundle:User:index.html.twig', array('logInUser' => $logInUser, 'cantTorneo' => $cantTorneo, 'pagination' => $pagination,
          'delete_form_ajax' => $deleteFormAjax->createView()));
    }

    /*****************************
    ****Inicio agregar usuario*****
    *******************************/

    public function addAction(){

      $user = new User();
      $form = $this->createCreateForm($user);

      $logInUser = $this->get('security.token_storage')->getToken()->getUser();
      $em = $this->getDoctrine()->getManager();

      $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";

      $cantTorneo = $em->createQuery($dql)->getResult();

      return $this->render('IAWUserBundle:User:add.html.twig', array('logInUser' => $logInUser, 'cantTorneo' => $cantTorneo, 'form' => $form->createView()));

    }

    private function createCreateForm(User $entidad){

      /*Creo el formulario con:
          * El formulario,
          * la entidad,
          * opciones:
              * generar ruta user/create
              * metodo POST
      */
      $form = $this->createForm(new UserType(), $entidad, array(
          'action' => $this->generateUrl('iaw_user_create'),
          'method' => 'POST'
      ));

      return $form;
    }


    public function createAction(Request $request){

      //Obtengo el formulario procesandolo con el objeto request
      $user = new User();
      $form = $this->createCreateForm($user);
      $form->handleRequest($request);

      if($form->isValid()){

        //Obtengo el password que se ingreso en el formulario
        $password = $form->get('password')->getData();

        $passwordConstraint = new Assert\NotBlank();
        $error = $this->get('validator')->validate($password,$passwordConstraint);

        if(count($error) == 0){

          //Codifico la constraseña
          $encoder = $this->container->get('security.password_encoder');
          $encoded = $encoder->encodePassword($user, $password);

          //El password codificado es el valor del password de la entidad
          $user->setPassword($encoded);

          //Rol indicado en el formulario
          $role = $form->get('role')->getData();

          //Agrego a roles el rol indicado en el form
          $user->setRoles(array('role' => $role));

          //Como no nos importa el email, se inserta el nombre del usuario
          $username = $form->get('username')->getData();
          $user->setEmail($username."@email.com");

          //Guardo en la base de datos
          $em = $this->getDoctrine()->getManager();
          $em->persist($user);
          $em->flush();

          //Genero el mensaje para mostrar
          $this->addFlash(
              'mensaje',
              'Nuevo usuario creado correctamente'
          );

          return $this->redirectToRoute('iaw_user_index');
        }
        else{
          $mensajeError = new FormError($error[0]->getMessage());
          $form->get('password')->addError($mensajeError);
        }
      }
      $logInUser = $this->get('security.token_storage')->getToken()->getUser();
      $em = $this->getDoctrine()->getManager();

      $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";

      $cantTorneo = $em->createQuery($dql)->getResult();

      //En caso de algun problema, renderizo el formulario
      return $this->render('IAWUserBundle:User:add.html.twig', array('logInUser' => $logInUser, 'cantTorneo' => $cantTorneo, 'form' => $form->createView()));
    }

    /*****************************
    ****Fin agregar usuario*****
    *******************************/


    /*****************************
    ****Inicio ver usuario*****
    *******************************/

    public function viewAction($id){

      $repository = $this->getDoctrine()->getRepository('IAWUserBundle:User');

      //Obtengo el usuario con el id indicado
      $user = $repository->find($id);

      //Verifico si existe el usuario con el id indicado
      if(!$user){
        throw $this->createNotFoundException('Usuario no encontrado');
      }

      $deleteForm = $this->createCustomForm($user->getId(), 'DELETE', 'iaw_user_delete');

      $logInUser = $this->get('security.token_storage')->getToken()->getUser();
      $em = $this->getDoctrine()->getManager();

      $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";

      $cantTorneo = $em->createQuery($dql)->getResult();

      return $this->render('IAWUserBundle:User:view.html.twig', array('logInUser' => $logInUser,'cantTorneo' => $cantTorneo, 'user' => $user,'delete_form' => $deleteForm->createView()));

    }

    /*****************************
    *******Fin ver usuario*****
    *******************************/


    /*****************************
    ****Inicio eliminar usuario***
    *******************************/

    public function deleteAction(Request $request, $id){

      $em = $this->getDoctrine()->getManager();
      //Obtengo el usuario con el id indicado
      $user = $em->getRepository('IAWUserBundle:User')->find($id);

      //Verifico si existe el usuario con el id indicado
      if(!$user){
        throw $this->createNotFoundException('Usuario no encontrado');
      }

      //Creo el formulario para eliminar
      $form = $this->createCustomForm($user->getId(), 'DELETE', 'iaw_user_delete');
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){

        //Verifico si se trata de una llamada ajax
        if($request->isXMLHttpRequest()){
          $res = $this->deleteUser($user->getRole(), $em, $user);
          return new Response(
            json_encode(array(
              'removed' => $res['removed'],
              'message' => $res['message'])),
              200,
              array('Content-Type' => 'application/json')
          );
        }
        //Fin  llamada ajax

        $res = $this->deleteUser($user->getRole(), $em, $user);

        $this->addFlash(
            $res['alert'],
            $res['message']
        );

        return $this->redirectToRoute('iaw_user_index');
      }
    }

    private function deleteUser($role, $em, $user){

      //No es correcto borrar un usuario administrador,
      //por ello, solo vamos a eliminar usuarios del tipo editor.
      if($role == 'ROLE_EDITOR'){
        //Borro el usuario
        $em->remove($user);
        $em->flush();

        $mensaje = 'Usuario eliminado correctamente';
        $removed = 1;
        $alert = 'mensaje';
        return array('removed' => $removed, 'message' => $mensaje, 'alert' => $alert);

      }
      elseif($role == 'ROLE_ADMIN'){
          $mensaje = 'EL usuario no ha sido eliminado';
          $removed = 0;
          $alert = 'mensajeError';
          return array('removed' => $removed, 'message' => $mensaje, 'alert' => $alert);

      }
    }

    /*****************************
    *******Fin eliminar usuario***
    *******************************/

    /*****************************
    ****Inicio editar usuario*****
    *******************************/

    public function editAction($id){

      $em = $this->getDoctrine()->getManager();
      $user = $em->getRepository('IAWUserBundle:User')->find($id);

      //Verifico si existe el usuario con el id indicado
      if(!$user){
        throw $this->createNotFoundException('Usuario no encontrado');
      }

      $form = $this->createEditForm($user);

      $logInUser = $this->get('security.token_storage')->getToken()->getUser();

      $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";

      $cantTorneo = $em->createQuery($dql)->getResult();

      return $this->render('IAWUserBundle:User:edit.html.twig', array('logInUser' => $logInUser, 'cantTorneo' => $cantTorneo,'user' => $user,'form' => $form->createView()));
    }

    private function createEditForm(User $entidad){

      /*Creo el formulario con:
          * El formulario,
          * la entidad,
          * opciones:
              * generar ruta user/update/{id}
              * metodo PUT
      */
      $form = $this->createForm(new UserType(), $entidad, array(
            'action' => $this->generateUrl('iaw_user_update', array('id' => $entidad->getId())),
            'method' => 'PUT'));
      return $form;
    }

    public function updateAction($id, Request $request){

      $em = $this->getDoctrine()->getManager();

      //Obtengo el usuario con el id indicado
      $user = $em->getRepository('IAWUserBundle:User')->find($id);

      //Verifico si existe el usuario con el id indicado
      if(!$user){
        throw $this->createNotFoundException('Usuario no encontrado');
      }

      //Obtengo el formulario procesandolo con el objeto request
      $form = $this->createEditForm($user);
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){
        //Recupero la constraseña indicada en el form
        $password = $form->get('password')->getData();
        //No siempre que se edita el usuario, se cambia la contraseña.
        //Si el usuario cambia su password, realizo la codificacion.
        if(!empty($password)){
          $encoder = $this->container->get('security.password_encoder');
          $encoded = $encoder->encodePassword($user, $password);
          $user->setPassword($encoded);
        }
        else{
          //La contraseña del usuario sigue siendo la misma
          $passwordBD = $this->recuperarPassword($id);
          $user->setPassword($passwordBD[0]['password']);
        }

        //Obtengo el role indicado en el form
        $role = $form->get('role')->getData();

        //Obtengo el roles actual del usuario
        $roles = $user->getRoles();

        //Elimino el roles actual
        $user->removeRole($roles[0]);

        //Agrego a roles el role indicado
        $user->setRoles(array('role' => $role));




        //Guardo en la base de datos
        $em->flush();

        //Genero el mensaje para mostrar
        $this->addFlash('mensaje', "Usuario actualizado correctamente");

        return $this->redirectToRoute('iaw_user_edit', array('id' => $user->getId()));
      }
      $logInUser = $this->get('security.token_storage')->getToken()->getUser();

      $dql = "SELECT COUNT(t.id) as nro FROM IAWTorneoBundle:Torneo t";

      $cantTorneo = $em->createQuery($dql)->getResult();
      //En caso de algun problema, renderizo el formulario
      return $this->render('IAWUserBundle:User:edit.html.twig', array('logInUser' => $logInUser, 'cantTorneo' => $cantTorneo, 'user', 'form' => $form->createView()));
    }

    /*****************************
    *******Fin editar usuario*****
    *******************************/


    private function createCustomForm($id, $method, $route){

      return $this->createFormBuilder()
          ->setAction($this->generateUrl($route, array('id' => $id)))
          ->setMethod($method)
          ->getForm();
    }

    private function recuperarPassword($id){
      //Recupero la password de un id dado.
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery(
          'SELECT u.password
           FROM IAWUserBundle:User u
           WHERE u.id = :id'
      )->setParameter('id',$id);

      $passwordBD = $query->getResult();
      return $passwordBD;
    }

}
