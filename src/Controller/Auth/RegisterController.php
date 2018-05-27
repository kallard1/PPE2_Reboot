<?php

namespace App\Controller\Auth;

use App\Entity\AddressCustomer;
use App\Entity\Customer;
use App\Form\CustomerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class RegisterController
 * @package App\Controller\Auth
 * @Route("/register")
 */
class RegisterController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="registration")
     * @Method("GET|POST")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if (null !== $this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $customer = new Customer();
        $address = new AddressCustomer();

        $address->setCustomer($customer);
        $customer->getAddresses()->add($address);

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($customer, $customer->getPlainPassword());
            $customer->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('registration.flash.message.success')
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render("Auth/Register/register.html.twig",
            [
                'form'  => $form->createView()
            ]);
    }
}
