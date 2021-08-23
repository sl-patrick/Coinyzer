<?php

namespace App\Controller;

use App\Service\CallApi;
use App\Entity\Watchlists;
use App\Entity\Cryptocurrencies;
use App\Entity\CryptocurrencyData;
use App\Repository\WatchlistsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CryptocurrenciesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CryptocurrencyDataRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CryptocurrencyController extends AbstractController
{
    /**
     * @Route("/cryptocurrencies", name="app_cryptocurrenciesRank")
     */
    public function rank(CryptocurrencyDataRepository $cryptocurrencyDataRepository, PaginatorInterface $paginator, Request $request, EntityManagerInterface $em): Response
    {
        // $allCryptocurrencies = $cryptocurrencyDataRepository->findAll();
        // $pagination = $paginator->paginate($allCryptocurrencies, $request->query->getInt('page', 1), 3);
        // limite par page * page + 1
        // $dql   = "SELECT a FROM App\Entity\CryptocurrencyData a ORDER BY a.market_cap DESC ";
        $dql = "SELECT a, b FROM App\Entity\CryptocurrencyData a JOIN a.cryptocurrencies b WHERE a.cryptocurrencies = b.id ORDER BY a.market_cap DESC";
        $query = $em->createQuery($dql);
    
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );

        
        return $this->render('cryptocurrency/index.html.twig', [
            'cryptocurrencies' => $pagination,
        ]);
    }

    /**
     * Undocumented function
     * 
     * @Route("/cryptocurrencies/userWatchlist", name="app_cryptocurrenciesWatchlist")
     */
    public function showWatchlist()
    {
        //vérifier si l'utilisateur est connecté.
        $user = $this->getUser();
        

        //récupérer les cryptomonnaies dans la watchlist de l'utilisateur.
        //afficher les cryptomonnaies.
    }

    /**
     * @Route("/cryptocurrency/{slug}/overview", name="app_CryptocurrencyOverview")
     */
    public function details(string $slug, CryptocurrenciesRepository $cryptocurrenciesRepository) :Response
    {
        $cryptocurrency = $cryptocurrenciesRepository
                ->findByNameOrFullname($slug);
        
           
        return $this->render('cryptocurrency/details.html.twig', [
            'cryptocurrency' => $cryptocurrency,
        ]);
    }

    /**
     * Permet d'ajouter ou de supprimer une cryptomonnaie dans la liste des favoris de l'utilisateur.
     * @Route("/addWatchlist/{id}", name="app_watchlist", methods={"POST"})
     */
    public function watchlistManager(int $id, WatchlistsRepository $watchlistRepo, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $cryptocurrency = $entityManager->getRepository(Cryptocurrencies::class)->find($id);
        
        if (!$user) {

            return $this->json(['code' => 403, 'message' => 'Connectez-vous'], 403);
        
        } elseif ($user) {

            $watchlist = $watchlistRepo->findOneBy(['users' => $user]);

            if ($watchlist == null) {

                $createWatchlist = new Watchlists();
                $createWatchlist->setUsers($user);
                $createWatchlist->addCryptocurrency($cryptocurrency);
                $entityManager->persist($createWatchlist);
                $entityManager->flush();

                return $this->json(['message' => 'create and add'], 200);

            } else {

                if ($cryptocurrency->likedByUser($user)) {  //si l'utilisateur a déjà ajouté cette cryptomonnaie à sa watchtlist on la supprime.
            
                    $watchlist = $watchlistRepo->findOneBy(['users' => $user]);
                    $watchlist->removeCryptocurrency($cryptocurrency);
                    $entityManager->flush();
        
                    return $this->json(['message' => 'remove'], 200);
                    
                } else {
        
                    $watchlist = $watchlistRepo->findOneBy(['users' => $user]);
                    $watchlist->addCryptocurrency($cryptocurrency);
                    $entityManager->flush();
                    
                    return $this->json(['message' => 'add'], 200);
                }  
            }
        } 
    }

    /**
     * Undocumented function
     *@Route("/cryptocurrencies/refresh", name="app_cryptocurrenciesRefresh", methods={"POST"})
     */
    public function updateByUser(Request $request, CallApi $callApi, PaginatorInterface $paginator, EntityManagerInterface $em)
    {
        //recupere le temps actuel
        $currentTime = time();
        
        //recupere la date de la derniere mise a jour
        $lastUpdateData = $em->getRepository(CryptocurrencyData::class)->findAll();
        
        foreach ($lastUpdateData as $value) {
            $time = $value->getUpdateAt();
        }

        //transformer la date de la derniere mise a jour en timestamp
        $timeToString = date_format($time, 'Y-m-d H:i:s');

        $timeToTimestamp = strtotime($timeToString);

        //si la derniere mise a jour + 30 secondes est egal au temps actuel ou est inferieur -> autoriser la mise a jour
        if ($timeToTimestamp + 30 === $currentTime OR $timeToTimestamp + 30 < $currentTime) {
            
            $callApi->updateCryptocurrencyData();
    
            $dql = "SELECT a, b FROM App\Entity\CryptocurrencyData a JOIN a.cryptocurrencies b WHERE a.cryptocurrencies = b.id ORDER BY a.market_cap DESC";
            $query = $em->createQuery($dql);
        
            $cryptocurrencies = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                3 /*limit per page*/
            );

        } else {

            $dql = "SELECT a, b FROM App\Entity\CryptocurrencyData a JOIN a.cryptocurrencies b WHERE a.cryptocurrencies = b.id ORDER BY a.market_cap DESC";
            $query = $em->createQuery($dql);
        
            $cryptocurrencies = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                3 /*limit per page*/
            );
        }
        
        $contents = $this->render('components/table_rank.html.twig', ['cryptocurrencies' => $cryptocurrencies,])->getContent();
        
        return new JsonResponse($contents);
    
    }
}
