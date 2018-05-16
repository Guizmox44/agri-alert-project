<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Events\Events;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction(UserInterface $user)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findBy(['user'=> $user-> getId()]);

        return $this->render('application/product/index.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request,UserInterface $user,EventDispatcherInterface $eventDispatcher)
    {
        $product = new Product();
        $form = $this->createForm('AppBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUser($user);
            if($product->getQuantity()<= $product->getAlert()) {
                $product->setHasAlert(true);
                $event= new GenericEvent($user,['product'=>$product]);
                $eventDispatcher->dispatch(Events::MAIL_ALERT, $event);
            }else{
                $product->setHasAlert(false);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            $this->addFlash(
                'success',
                'votre produit a été crée !'
            );
            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('application/product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('application/product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product,UserInterface $user,EventDispatcherInterface $eventDispatcher)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('AppBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if(($product->getQuantity())<= ($product->getAlert())){
                $product->setHasAlert(true);
                }
            else{
                $product->setHasAlert(false);
            }
            $this->getDoctrine()->getManager()->flush();
            if($product->getHasAlert()===true){
                $event= new GenericEvent($user,['product'=>$product]);
                $eventDispatcher->dispatch(Events::MAIL_ALERT, $event);

            }
            $this->addFlash(
                'success',
                'votre produit a été modifié!'
            );
            return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
        }

        return $this->render('application/product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     *
     * @Route("/search", name="search_product")
     *  @Method({"POST"})
     */
    public function searchAction(Request $request,UserInterface $user)
    {

        $search = $request->request->get('label');

        $results = $this->getDoctrine()->getRepository(Product::class)->findLike($search,$user);
        if(!$results) {
            return $this->json(false);
        }
        else {
            foreach ($results as $result) {

                $datas = [
                    'success' => true,
                    'label' => $result->getLabel(),
                    'id' => $result->getId(),

                ];
                $test[] = $datas;
            }

            //dump($test);die;
            return $this->json($test);
        }
    }


}
