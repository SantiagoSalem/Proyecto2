<?php

namespace IAW\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('IAWUserBundle:Default:index.html.twig');
    }
}
