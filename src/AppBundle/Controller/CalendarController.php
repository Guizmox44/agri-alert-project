<?php

namespace AppBundle\Controller;

use AppBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Task;
use Symfony\Component\Security\Core\User\UserInterface;

class CalendarController extends Controller
{
    /**
     * @Route("/calendar", name="calendar_main")
     * @Method("GET")
     */
    public function mainAction(UserInterface $user)
    {
        /*On creer un formulaire pour l'ajout d'un nouvel evenement dans le calendrier*/
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->get('day')->setData(new \Datetime());

        /*On recupere toute les evenement de l'utilisateur*/
        $tasks = $this->getDoctrine()->getRepository(Task::class)
        ->findBy(['user'=> $user-> getId()]);


        return $this->render('application/calendar/main.html.twig', [
            'tasks' => $tasks,
            'add_form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/calendar/add", name="calendar_add")
     * @Method("POST")
     */
    public function addAction(Request $request)
    {
        $user = $this->getUser();
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            $this->addFlash(
                'success',
                'l\'evenement est bien ajouté!'
            );

        }
        else {
            // Message d'erreur
        }
        return $this->redirectToRoute('calendar_main');
        /*return $this->render('application/calendar/main.html.twig', [
            'add_form' => $form->createView(),
        ]);*/

    }

    /**
     * @Route("/calendar/show/{day}", name="calendar_show")
     * @Method("GET")
     */
    public function showAction($day, UserInterface $user)
    {

        $repository = $this->getDoctrine()->getRepository(Task::class);

        $query = $repository->createQueryBuilder('t')
            ->where('t.day like :day')
            ->andWhere('t.user = :user')
            ->setParameter('day', $day.'%')
            ->setParameter('user', $user->getId())
            ->getQuery();
        $tasks = $query->getResult();

        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->get('day')->setData(new \Datetime($day));


        //@TODO remplir la method
        //@TODO changer le chemin
        return $this->render('application/calendar/show.html.twig', [
            'tasks' => $tasks,
            'add_form'=>$form->createView(),
            'day'=>$day
        ]);
    }

    /**
     * @Route("/calendar/edit", name="calendar_edit")
     * @Method("POST")
     */
    public function editAction(Request $request, UserInterface $user)
    {
        $data = $request->request->all();

        $task = $this->getDoctrine()->getManager()->getRepository(Task::class)->find($data['id']);
        if($user != $task->getUser()) {
            $result = [
                'success'=> false,
            ];
        }
        else {

            ($task->getTitle() != $data['title']) ? $task->setTitle($data['title']) : '';
            $task->setDay(new \Datetime($data['day'].$data['time']));
            ($task->getMessage() != $data['message']) ? $task->setMessage($data['message']) : '';

            $this->getDoctrine()->getManager()->flush();

            $result = [
                'success' => true,
                'title' => $data['title'],
                'message' => $data['message'],
                'day' => $data['day'],
                'time' => $data['time'],
            ];
        }

        return $this->json($result);
    }

    /**
     * @Route("/calendar/delete", name="calendar_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request,EntityManagerInterface $em)
    {
        $id=$request->request->get('idTask');
        //dump($id);die;

        $task = $this->getDoctrine()->getManager()->getRepository(Task::class)->find($id);
        $em->remove($task);
        $em->flush();

        $result=[
            'success'=>true,
        ];
        //@TODO remplir la method
        return $this->json($result);
    }



    /**
     * @Route("/calendar/update", name="calendar_update")
     * @Method("POST")
     */
    public function updateAction(Request $request)
    {
        //Récuperation des données envoyées
        $idTask=$request->request->get('idTask');
        $idDay=$request->request->get('idDay');

        //Récuperation de la tâche associé à $idTask
        $task = $this->getDoctrine()->getManager()->getRepository(Task::class)->find($idTask);

        $date = $task->getDay();
        //On stock le mois et l'année
        $month = $date->format('m');
        $year = $date->format('Y');

        //on set le day de la tache avec le nouveau jour
        $task->setDay(new\DateTime($year. '-'. $month . '-' . $idDay));
        //on flush
        $this->getDoctrine()->getManager()->flush();

        //Petit message de confirmation envoyé en json
        return $this->json('true');
    }


}
