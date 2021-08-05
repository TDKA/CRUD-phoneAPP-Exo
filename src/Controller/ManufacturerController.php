<?php

namespace App\Controller;

use App\Entity\Manufacturer;
use App\Form\ManufacturerType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ManufacturerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManufacturerController extends AbstractController
{
    /**
     * @Route("/manufacturer/create", name="manufacturerCreate")
     * @Route("/manufacturer/edit/{id}", name="editManufacturer", priority=2)
     */
    public function create(Request $req, EntityManagerInterface $manager): Response
    {
        $manufacturer = new Manufacturer();

        $form = $this->createForm(ManufacturerType::class, $manufacturer);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            // Images
            $imgSended = $form->get('imageLogo')->getData();
            if ($imgSended) {

                $imgName = pathinfo($imgSended->getClientOriginalName(), PATHINFO_FILENAME);
                $newImg = uniqid() . '.' . $imgSended->guessExtension();

                $imgSended->move(
                    $this->getParameter('manufacturer_images'),
                    $newImg
                );

                $manufacturer->setImageLogo($newImg);
            }


            $manager->persist($manufacturer);
            $manager->flush();

            return $this->redirectToRoute('phone');
        }

        return $this->render('manufacturer/create.html.twig', [

            'form' => $form->createView()
        ]);
    }

    /**
     * 
     * 
     * @Route("/manufacturer", name="showAllManufacturers")
     */
    public function index(ManufacturerRepository $repo)
    {
        $manufacturers = $repo->findAll();

        return $this->render("manufacturer/index.html.twig", [
            'manufacturers' => $manufacturers
        ]);
    }

    /**
     * @Route("/manufacturer/delete/{id}", name = "deleteManufacturer", priority=2);
     * 
     */
    public function delete(Manufacturer $manufacturer, EntityManagerInterface $manager)
    {


        $manager->remove($manufacturer);
        $manager->flush();



        return $this->redirectToRoute('showAllManufacturers');
    }
}
