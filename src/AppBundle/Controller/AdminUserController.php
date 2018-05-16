<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends Controller
{
    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function adminUserAction()
    {
        $allUsers = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('application/admin/user/adminUser.html.twig', [
            'allUsers' => $allUsers
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="admin_user_show")
     */
    public function adminUserShowAction(User $user)
    {
        return $this->render('application/admin/user/adminUserShow.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @ROute("/admin/user/{id}/edit", name="admin_user_edit")
     */
    public function adminUserEditAction(Request $request, User $user, UserPasswordEncoderInterface $encoder)
    {
        $deleteForm = $this->createDeleteForm($user);
        $form = $this->createForm(UserType::class, $user);

        $olderPassword = $user->getPassword();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if (!empty($user->getPassword())){
                $encoded = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($encoded);
            } else {
                $user->setPassword($olderPassword);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("admin-user-edit", "Utilisateur modifié");

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('application/admin/user/adminUserEdit.html.twig', [
            'user' => $user,
            'edit_form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),

        ]);
    }

    /**
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     */
    public function adminUserDeleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

        }

        return $this->redirectToRoute('admin_user');
    }


    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

}
