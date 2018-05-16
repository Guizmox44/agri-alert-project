<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Animal;
use AppBundle\Entity\Species;
use AppBundle\Form\AnimalType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AnimalController extends Controller
{
    /**
     * @Route("/cheptel/{tag}", name="cheptel_species")
     */
    public function cheptelSpeciesAction($tag)
    {
        $speciesRepository = $this->getDoctrine()->getRepository(Species::class);
        $check = $speciesRepository->findBy(['wording' => $tag]);

        if ($check)
        {
            $user = $this->getUser();
            $species = $speciesRepository->finBySpecies($tag, $user);

        } else {
            throw $this->createNotFoundException('Erreur, page non trouvé');
        }

        return $this->render('application/cheptel/cheptel-species.html.twig', [
            'species' => $species,
            'tag' => $tag
        ]);
    }

    /**
     * @Route("/cheptel/{tag}/show/{id}", name="cheptel_show", requirements={"id"="\d+"})
     */
    public function cheptelShowAction(Animal $animal, $tag, $id)
    {
        $check = $this->getDoctrine()->getRepository(Animal::class)->find($id);

        $getSpecies = $check->getSpecies()->getWording();

        if ($getSpecies === $tag)
        {

        }else{
            throw $this->createNotFoundException('Erreur, page non trouvé');
        }

        return $this->render('application/cheptel/cheptel_show.html.twig', [
            'animal' => $animal,
            'tag' => $tag
        ]);
    }


    /**
     * @Route("/cheptel/{tag}/add", name="cheptel_add")
     */
    public function cheptelAddAction($tag, Request $request)
    {
        $check = $this->getDoctrine()->getRepository(Species::class)->findBy(['wording' => $tag]);


        $em = $this->getDoctrine();
        if ($check)
        {
            $animal = new Animal();
            $form = $this->createForm(AnimalType::class, $animal, [
                'request' => $request,
                'doctrine' => $em,
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $species = $this->getDoctrine()->getRepository(Species::class)->getOneSpecies($tag);
                $animal->setUser($this->getUser());
                $animal->setSpecies($species);

                $em = $this->getDoctrine()->getManager();
                $em->persist($animal);
                $em->flush();

                $this->addFlash("success", "Votre animal a bien été ajouté.");
                return $this->redirectToRoute('cheptel_species',['tag' => $tag]);
            }

        }else{
            throw $this->createNotFoundException('Erreur, page non trouvé');
        }

        return $this->render('application/cheptel/cheptel_add.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag
        ]);
    }

    /**
     * @Route("/cheptel/{tag}/edit/{id}", name="cheptel_edit", requirements={"id"="\d+"})
     */
    public function cheptelEditAction(Animal $animal, $tag, Request $request)
    {
        $check = $this->getDoctrine()->getRepository(Species::class)->findBy(['wording' => $tag]);

        $em = $this->getDoctrine();
        if ($check)
        {
            $deleteForm = $this->createDeleteForm($animal, $tag);
            $form = $this->createForm(AnimalType::class, $animal, [
                'request' => $request,
                'doctrine' => $em,
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $em->getManager()->flush();

                $this->addFlash("success", "Votre animal a bien été modfifé.");
                return $this->redirectToRoute('cheptel_species',['tag' => $tag]);
            }

        }else{
            throw $this->createNotFoundException('Erreur, page non trouvé');
        }
        return $this->render('application/cheptel/cheptel_edit.html.twig',[
            'form' => $form->createView(),
            'tag' => $tag,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/cheptel/{tag}/delete/{id}", name="cheptel_delete", requirements={"id"="\d+"})
     */
    public function cheptelDeleteAction(Request $request, Animal $animal, $tag)
    {
        $form = $this->createDeleteForm($animal, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($animal);
            $em->flush();

            $this->addFlash("success", "Votre animal a bien été supprimé.");
            return $this->redirectToRoute('cheptel_species',['tag' => $tag]);
        }

    }

    private function createDeleteForm(Animal $animal, $tag)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cheptel_delete', ['tag' => $tag, 'id' => $animal->getId()]))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
