<?php
/**
 * Created by PhpStorm.
 * User: mint
 * Date: 08/05/18
 * Time: 14:24
 */

namespace AppBundle\Eventsubscriber;

use AppBundle\Events\Events;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class AlertNotificationSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        // On injecte notre expediteur et la classe pour envoyer des mails
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // le nom de l'event et le nom de la fonction qui sera déclenché
            Events::MAIL_ALERT => 'onMailNotification',
        ];
    }

    public function onMailNotification(GenericEvent $event)
    {
        /** @var User $user */

        $user = $event->getSubject();
        $product=$event->getArgument('product')->getLabel();

        $subject = "Alerte stock Agri-Alerte";
        $body = "Le produit ".$product. " a atteint le niveau d'alerte";
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setTo($user->getEmail())
            ->setFrom('agri.alert2@gmail.com')
            ->setBody($body, 'text/html')
        ;

        $this->mailer->send($message);
    }
}