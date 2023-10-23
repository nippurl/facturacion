<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController
{
    /**
     * @Route("/usuario", name="usuario")
     */
    public function index(): Response
    {
        return $this->render('usuario/index.html.twig', [
            'controller_name' => 'UsuarioController',
        ]);
    }

    /**
     * @Route("/logout", name="usuario_logout")
     */
    public function logout(Request $request)
    {
        $user = $this->getUser();
        if ($this->getUser()) {


            $this->getUser()
                ->eraseCredentials();

            /*         $this->getUser()
                          ->setAuthenticated(false);
                     $this->getUser()
                          ->getAttributeHolder()
                          ->remove(‘referer’);
                     $this->getUser()
                          ->getParameterHolder()
                          ->removeNamespace(‘referer’);
                     $this->getUser()
                          ->getParameterHolder()
                          ->clear();
              */
        }

        session_unset();
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        $session = $this->get('session');
        /* @var $session  \Symfony\Component\HttpFoundation\Session\Session */
        $session->remove('security_client');
        $session->clear();
        $this->get('security.token_storage')->setToken(null);

        $request->getSession()->invalidate();
        $session = $request->getSession();
        $session->clear('user');
        $session->remove('user');
        unset($session);



        // throw new \Exception('this should not be reached!');
    //    return $this->render('@EasyAdmin/default/logout.html.twig');
        // return array();
        return $this->render('usuario/logout.html.twig', []);
    }
}
