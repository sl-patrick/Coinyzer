<?php

namespace App\Controller;

use App\Entity\Contact;
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
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() AND $form->isValid()) {
            $contact = $form->getData();
            dump($contact->getEmail());

            // envoie du mail 
            $email = (new Email())
                ->from($contact->getEmail())
                ->to(new Address('contact@coinyzer.com', 'Coinyzer'))
                ->subject('contacte support')
                ->text('Lorem ipsum...');
            
            $mailer->send($email);
            dump($email);

            //message pour l'utilisateur.
            $this->addFlash('success', 'Votre message à bien été enregistré');
            
            // return $this->redirectToRoute('app_contact');
            // $contact->setCreatedAt(new \DateTime);
            // dd($form);
        }


        return $this->render('main/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }

}
