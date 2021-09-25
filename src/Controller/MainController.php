<?php

namespace App\Controller;

use App\Entity\Cryptocurrencies;
use App\Form\ContactType;
use App\Form\SearchCryptocurrencyType;
use App\Repository\CryptocurrenciesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request, CryptocurrenciesRepository $cryptocurrenciesRepository): Response
    {    
        //Création du formulaire
        $searchForm = $this->get('form.factory')->createNamed('search-form', SearchCryptocurrencyType::class);

        $searchForm->handleRequest($request);
        
        //Vérification si le formulaire est envoyé et valide
        if ($searchForm->isSubmitted() AND $searchForm->isValid()) {
           
            $value = $searchForm->getData(); //stock la valeur du formulaire

            $valueToLower = strtolower($value['find']);
            
            //on vérifie si la valeur du formulaire correspond a une valeur en base de données
            $cryptocurrency = $cryptocurrenciesRepository->findByNameOrFullname($valueToLower);

            if ($cryptocurrency instanceof Cryptocurrencies) {

                return $this->redirectToRoute('app_CryptocurrencyOverview', ['slug' => $valueToLower]);
            }
        }

        return $this->render('main/home.html.twig', [
            'searchForm' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() AND $form->isValid()) {
            $contact = $form->getData();

            // envoie du mail 
            $email = (new Email())
                ->from($contact['email'])
                ->to('coinyzer@psamelhori.fr')
                ->subject('Support')
                ->text(htmlspecialchars(strip_tags($contact['message'])));
            
            $mailer->send($email);

            //message pour l'utilisateur.
            $this->addFlash('success', 'Merci de votre message ! Notre équipe vous répondra dans les meilleurs délais.');
            
        }


        return $this->render('main/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }

}
