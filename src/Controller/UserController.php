<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="welcome")
     */
    public function welcome()
    {
        
        /* @var $user \App\Entity\User */
        $user = $this->getUser();
        
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirect('login');
        }
                        
        return $this->render('user/welcome.html.twig', [
            'name' => $user->getUsername()
        ]);
    }
}
