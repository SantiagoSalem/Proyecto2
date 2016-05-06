<?php

namespace IAW\ParticipanteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('IAWParticipanteBundle:Default:index.html.twig');
    }
}
