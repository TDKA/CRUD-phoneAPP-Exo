<?php

namespace App\Controller;


use App\Entity\Phone;
use App\Form\PhoneType;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class PhoneController extends AbstractController
{
    /**
     * @Route("/phone", name="phone")
     */
    public function index(PhoneRepository $repo): Response
    {
        $phones = $repo->findAll();

        return $this->render('phone/index.html.twig', [
            'controller_name' => 'PhoneController',
            'phones' => $phones

        ]);
    }

    /**
     * @Route("/phone/{id}", name="showPhone")
     * 
     */
    public function showOne(Phone $phone)
    {


        return $this->render("phone/show.html.twig", [
            'phone' => $phone
        ]);
    }

    /**
     * 
     * @Route("/phone/create", name="createPhone", priority=2)
     * @Route("/phone/edit/{id}", name="editPhone", priority=2)
     * 
     * 
     */
    public function create(Request $req, EntityManagerInterface $manager, Phone $phone = null, UserInterface $user): Response
    {

        $modeEdition = true;

        if (!$phone) {

            $phone = new Phone();
            $modeEdition = false;
        }

        if ($user != $phone->getAuthor() && $modeEdition) {

            return $this->redirectToRoute('phone');
        }

        $form = $this->createForm(PhoneType::class, $phone);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$modeEdition) {

                $phone->setCreatedAt(new \DateTime());

                $phone->setAuthor($user);
            }

            // Images
            $imgSended = $form->get('image')->getData();
            if ($imgSended) {

                $imgName = pathinfo($imgSended->getClientOriginalName(), PATHINFO_FILENAME);
                $newImg = uniqid() . '.' . $imgSended->guessExtension();

                $imgSended->move(
                    $this->getParameter('phone_images'),
                    $newImg
                );

                $phone->setImage($newImg);
            }


            $manager->persist($phone);
            $manager->flush();

            return $this->redirectToRoute('showPhone', [
                "id" => $phone->getId()

            ]);
        }


        return $this->render('phone/create.html.twig', [
            'form' => $form->CreateView(),
            'modeEdition' => $modeEdition

        ]);
    }

    /**
     * @Route("/phone/delete/{id}", name = "deletePhone", priority=2);
     * 
     */
    public function delete(Phone $phone, EntityManagerInterface $manager)
    {


        $manager->remove($phone);
        $manager->flush();



        return $this->redirectToRoute('phone');
    }
}
