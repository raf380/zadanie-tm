<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Description of UserControllerTest
 *
 * @author rafal
 */
class UserControllerTest extends WebTestCase {

     
    
    public function test_rejestracja_nowego_uzytkownika() {
                
        //given
        $client = static::createClient();
        
        
        $json = json_encode([
            'usermail' => 'raf_test2',
            'plainPassword' => 'password'
        ]);
        //when
        $client->request('POST', '/api/user', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], $json);
        
        $response = $client->getResponse();
        
        //then
        $this->assertEquals(\Symfony\Component\HttpFoundation\Response::HTTP_CREATED, $response->getStatusCode());
        $id = json_decode($response->getContent(), true);
        $this->assertTrue(is_int($id));
        
        
        /* @var $em \Doctrine\ORM\EntityManagerInterface */
        $em = $client->getContainer()->get('doctrine')->getEntityManager();
        $user = $em->find(\App\Entity\User::class, $id);
        $em->remove($user);
        $em->flush();
    }
    
    

}
