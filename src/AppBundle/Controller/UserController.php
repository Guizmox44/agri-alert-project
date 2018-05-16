<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Form\UserResetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error,
        ]);
    }

    /**
     * @Route("/signin", name="signin")
     */
    public function signinAction(Request $request, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $role = $this->getDoctrine()->getRepository(Role::class)->findOneBy(['code' => 'ROLE_USER']);

            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $user->setRole($role);

            $codePostal = $user->getZipCode();


            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            $this->container->get('meteo.data')->weatherManager();

            $this->addFlash("success", "Votre compte a bien été crée. Merci de vous connecter.");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('security/signin.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function profilAction()
    {
        $user = $this->getUser();


        return $this->render('security/profil.html.twig',[
            'user' => $user
        ]);
    }


    /**
     * @Route("/profil/edit", name="profil_edit")
     */
    public function profilEdit(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();

        $olderPassword = $user->getPassword();

        $form = $this->createForm(UserType::class, $user);

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

            $this->addFlash("success", "Votre profil a été mis à jour.");

            return $this->redirectToRoute('profil');
        }


        return $this->render('security/profil_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/reset", name="reset")
     */
    public function resetAction(Request $request, \Swift_Mailer $mailer, UserPasswordEncoderInterface $encoder)
    {
        //Instanciation d'un nouvelle utilisateur
        $user = new User();

        $form = $this->createForm(UserType::class, $user, array(
            'reset_password' => true,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() /*&& $form->isValid()*/)
        {
            $email = $user->getEmail();
            $account = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($account) {

                //Generation, encodage et sauvegarde d'un nouveau mot de passe
                $password =  $this->generatePassword();
                $encoded = $encoder->encodePassword($user, $password);
                $account->setPassword($encoded);

                $this->getDoctrine()->getManager()->flush();

                $message = (new \Swift_Message('Votre nouveau mot de passe'))
                    ->setFrom('agri.alert2@gmail.com')
                    ->setTo($account->getEmail())
                    ->setBody(
                        $this->renderView(
                            'application/emails/reset.html.twig', [
                                'password' => $password
                            ],
                            'text/html'
                            )
                        );
                $message->setContentType("text/html");
                $mailer->send($message);

           } else {
               throw $this->createNotFoundException('Votre email n\'est pas valide');
           }

            $this->addFlash("reset", "Votre mot de passe a bien été modifié. Consultez vos e-mails");
            return $this->redirectToRoute('login');
        }

        return $this->render('security/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    private  function generatePassword()
    {
        $rand = mt_rand(8, 10);
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-=+?";
        $password = substr(str_shuffle( $chars ),0, $rand );

        return $password;
    }
}
