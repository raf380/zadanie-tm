<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */
class UserController extends AbstractController {

    /**
     * @Route("/user/{id}", name="read", methods={"GET"})
     */
    public function readAction(User $user, SerializerInterface $serilizer) {
        $data = $serilizer->serialize($user, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/user", name="register", methods={"POST"})
     */
    public function createAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Doctrine\ORM\EntityManagerInterface $entityManager) {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user, array('csrf_protection' => false));

        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                    $passwordEncoder->encodePassword(
                            $user,
                            $form->get('plainPassword')->getData()
                    )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return new JsonResponse($user->getId(), Response::HTTP_CREATED);
        }

        $errors = [];
        /* @var $error \Symfony\Component\Form\FormError */
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/user/{id}", name="update", methods={"PUT"})
     */
    public function updateAction(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder, \Doctrine\ORM\EntityManagerInterface $entityManager) {
        $form = $this->createForm(RegistrationFormType::class, $user, array('csrf_protection' => false));
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                    $passwordEncoder->encodePassword(
                            $user,
                            $form->get('plainPassword')->getData()
                    )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return new JsonResponse('OK', Response::HTTP_OK);
        }

        $errors = [];
        /* @var $error \Symfony\Component\Form\FormError */
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/user/{id}", name="delete", methods={"DELETE"})
     */
    public function deleteAction(User $user, \Doctrine\ORM\EntityManagerInterface $entityManager) {

        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse('OK', Response::HTTP_OK);
    }

}
