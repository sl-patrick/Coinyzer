<?php

namespace App\Controller;

use App\Entity\Watchlists;
use App\Entity\Cryptocurrencies;
use App\Repository\WatchlistsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CryptocurrenciesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CryptocurrencyDataRepository;
use App\Service\CallApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CryptocurrencyController extends AbstractController
{
    /**
     * @Route("/cryptocurrencies", name="app_cryptocurrenciesRank")
     */
    public function rank(CryptocurrencyDataRepository $cryptocurrencyDataRepository): Response
    {
        $allCryptocurrencies = $cryptocurrencyDataRepository->findByMarketCapGreater();
        // dd($allCryptocurrencies);

        
        return $this->render('cryptocurrency/index.html.twig', [
            'cryptocurrencies' => $allCryptocurrencies,
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
    public function updateByUser(Request $request, CallApi $callApi, CryptocurrencyDataRepository $cryptocurrencyDataRepository)
    {
        
        $data = [];

        $callApi->updateCryptocurrencyData();

        $cryptocurrencies = $cryptocurrencyDataRepository->findByMarketCapGreater();

        $contents = $this->renderView('components/table_rank.html.twig', [
            'cryptocurrencies' => $cryptocurrencies,
            
        ]);

        $json = json_encode($contents);

        dd($json);


        
        // ob_start();
        // require '../templates/components/table_rank.html.twig';
        // $table = ob_get_contents();
        // ob_get_clean();
        // $data = json_encode($table);



        // foreach ($cryptocurrencies as $key => $value) {
        //     $data[$key]['name'] = $value->getCryptocurrencies()->getName();
        //     $data[$key]['fullname'] = $value->getCryptocurrencies()->getFullname();
        //     $data[$key]['price'] = $value->getPrice();
        //     $data[$key]['market_cap'] = $value->getMarketCap();
        //     $data[$key]['volume'] = $value->getVolume24h();
        //     $data[$key]['supply'] = $value->getCirculatingSupply();
        //     $data[$key]['update_at'] = $value->getUpdateAt();   
        // }
        
        return $this->json($data);

    
    }
}
