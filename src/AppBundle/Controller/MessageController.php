<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\Response;
use AppBundle\Form\MessageType;
use AppBundle\Form\ResponseType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class MessageController extends Controller
{
    /**
     * @Route("/message", name="message_list")
     */
    public function listAction(Request $request)
    {

        $user = $this->getUser();

        $getMessages = $this->getDoctrine()->getRepository(Message::class)->findBy(['user' => $user ]);


        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $message->setUser($this->getUser());

           $em =  $this->getDoctrine()->getManager();
           $em->persist($message);
           $em->flush();

            $this->addFlash("success", "Votre message a bien été ajouté.");
            return $this->redirectToRoute('message_list');
        }

        return $this->render('application/message/list.html.twig', [
            'getMessages' => $getMessages,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/message/{id}", name="message_show")
     */
    public function showAction(Message $message, Request $request)
    {
        $getReponses = $this->getDoctrine()->getRepository(Response::class)->findBy(['message' => $message]);
        $reponse = new Response();

        $form = $this->createForm(ResponseType::class, $reponse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $userCode = $this->getUser()->getRole()->getCode();

            $reponse->setAuthor($userCode);
            $reponse->setMessage($message);

            $em = $this->getDoctrine()->getManager();
            $em->persist($reponse);
            $em->flush();

            $this->addFlash("success", "Votre réponse a bien été enregistrée.");
            return $this->redirectToRoute('message_show', ['id' => $message->getId()]);
        }

        return $this->render('application/message/show.html.twig', [
            'getReponses' => $getReponses,
            'message' => $message,
            'form' => $form->createView()
        ]);
    }
}
