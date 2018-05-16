<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\Response;
use AppBundle\Entity\User;
use AppBundle\Form\MessageType;
use AppBundle\Form\ResponseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminMessageController extends Controller
{
    /**
     * @Route("/admin/message", name="admin_message")
     */
    public function adminMessageAction()
    {
        $messages = $this->getDoctrine()->getRepository(User::class)->getUserAndMessages();


        return $this->render('application/admin/message/message.html.twig', [
            'messages' => $messages
        ]);
    }

    /**
     * @Route("/admin/message/{id}/chat", name="admin_message_chat")
     */
    public function adminMessageChatAction(Request $request, $id, Message $message)
    {
        $messages = $this->getDoctrine()->getRepository(Message::class)->getMessageAndResponses($id);

        $updateStatus = $this->updateForm($message);

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

            $this->addFlash("admin-reponse", "Votre réponse a bien été enregistrée.");
            return $this->redirectToRoute('admin_message_chat', ['id' => $message->getId()]);
        }

        return $this->render('application/admin/message/message-chat.html.twig', [
            'messages' => $messages,
            'form' => $form->createView(),
            'updateSatus' => $updateStatus->createView(),
        ]);
    }


    /**
     * @Route("/admin/message/{id}/status", name="admin_message_status")
     */
    public function adminMessageStatus(Request $request, Message $message)
    {
        $form = $this->updateForm($message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $message->setClosed(1);

            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        return $this->redirectToRoute('admin_message');
    }


    private function updateForm(Message $message)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_message_status', array('id' => $message->getId())))
            ->setMethod('POST')
            ->getForm()
            ;
    }

}
