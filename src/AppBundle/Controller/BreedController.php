<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Breed;
use AppBundle\Entity\Animal;
use AppBundle\Form\BreedType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;


class BreedController extends Controller
{
    /**
     * @Route("breed/{id}/list", name="breed_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Breed $breed)
    {
        // Liste des animaux d'une race
        $animals = $this->getDoctrine()->getRepository(Animal::class)->findBy(['breed' => $breed->getId()]);

            return $this->render('application/breed/index.html.twig', [
                'animals' => $animals,
                'breed' => $breed,
            ]);
    }

    /**
     * Creates a new breed entity.
     *
     * @Route("breed/new", name="breed_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, UserInterface $user)
    {
        $breed = new Breed();
        $form = $this->createForm('AppBundle\Form\BreedType', $breed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($breed);
            $em->flush();

            return $this->redirectToRoute('breed_index', array('id' => $breed->getId()));
        }

        return $this->render('application/breed/new.html.twig', array(
            'breed' => $breed,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing breed entity.
     *
     * @Route("breed/{id}/edit", name="breed_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Breed $breed)
    {
        $deleteForm = $this->createDeleteForm($breed);
        $editForm = $this->createForm('AppBundle\Form\BreedType', $breed);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('breed_edit', array('id' => $breed->getId()));
        }

        return $this->render('application/breed/edit.html.twig', array(
            'breed' => $breed,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a breed entity.
     *
     * @Route("breed/{id}/delete", name="breed_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Breed $breed)
    {
        $form = $this->createDeleteForm($breed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($breed);
            $em->flush();
        }

        return $this->redirectToRoute('breed_index');
    }

    /**
     * Creates a form to delete a breed entity.
     *
     * @param Breed $breed The breed entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Breed $breed)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('breed_delete', array('id' => $breed->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
