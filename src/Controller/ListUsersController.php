<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ListUsersController extends Controller
{
    /**
     * @Route("/list/users", name="list_users")
     */
    public function index()
    {
        return $this->render('list_users/index.html.twig', [
            'controller_name' => 'ListUsersController',
        ]);
    }

    public function listUsers()
    {
    $repository = $this->getDoctrine()->getRepository(User::class);
    $users = $repository->findAll();


    if (!$users) {
        throw $this->createNotFoundException(
            'No users found'.$id
        );
    }

    return new Response('The list of users: '.$users->getUsername());

    // or render a template
    // in the template, print things with {{ users.username }}
    // return $this->render('users/show.html.twig', ['users' => $users]);
    }
}
