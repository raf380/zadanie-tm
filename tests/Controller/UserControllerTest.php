<?php

declare(strict_types=1);

namespace App\Test\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Description of UserControllerTest
 *
 * @author rafal
 */
class UserControllerTest extends WebTestCase {

    
    /**
     * @dataProvider getApiLinks
     */
    public function test_wywolanie_api_bez_autoryzacji_przenosi_do_logowania($method, $uri) {
        //given
        $client = static::createClient();

        
        $json = json_encode([
            'usermail' => 'raf_test',
            'plainPassword' => 'password'
        ]);
        //when
        $client->request($method, $uri, [], [], [
            'CONTENT_TYPE' => 'application/json'
                ], $json);

        $response = $client->getResponse();
        $przekierowanie = $response->isRedirect('/login');
        //then
        $this->assertTrue($przekierowanie);
    }
    
    public function getApiLinks(): array {
        return [
            ['POST', '/api/user'],
            ['GET', '/api/user/1'],
            ['PuT', '/api/user/1'],
            ['DELETE', '/api/user/1'],
        ];
    }
    
    public function test_rejestracja_nowego_uzytkownika() {
                
        //given
        $client = static::createClient();

        /* @var $em EntityManagerInterface */
        $em = $client->getContainer()->get('doctrine')->getManager();
        $operator = new User();
        $operator->setUsermail('operator_' . microtime(true));
        $operator->setPassword('password');
        $em->persist($operator);
        $em->flush();
        $this->autoryzuj($client, $operator->getId());

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
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $id = json_decode($response->getContent(), true);
        $this->assertTrue(is_int($id));

        $user = $em->find(User::class, $id);
        $em->remove($user);
        $em->remove($operator);
        $em->flush();
    }

    protected function autoryzuj(KernelBrowser $client, int $id) {
        $container = $client->getContainer();
        $session = $container->get('session');
        $em = $container->get('doctrine')->getManager();
        $person = $em->find(User::class, $id);

        $token = new UsernamePasswordToken($person, null, 'main', $person->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();

        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

}
