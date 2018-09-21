<?php

namespace App\Controller;

use App\Form\Type\PaymentType;
use App\PaymentGateway\PaymentGatewayChain;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     *
     * @param Request $request
     * @param PaymentGatewayChain $paymentGatewayChain
     *
     * @return Response
     */
    public function index(Request $request, PaymentGatewayChain $paymentGatewayChain)
    {
        $form = $this->createForm(PaymentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($paymentGatewayChain->submitPayment($form->getData())) {
                $this->addFlash('success', 'The payment was successful!');
            } else {
                $this->addFlash('danger', 'Unable to process the payment.');
            }

            return $this->redirectToRoute('index');

        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
