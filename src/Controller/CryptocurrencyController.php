<?php

namespace App\Controller;

use App\Service\CallApi;
use App\Entity\Cryptocurrencies;
use App\Entity\CryptocurrencyData;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CryptocurrenciesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CryptocurrencyDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CryptocurrencyController extends AbstractController
{
    /**
     * @Route("/cryptocurrencies", name="app_cryptocurrenciesRank")
     */
    public function rank(CryptocurrencyDataRepository $cryptocurrencyDataRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $cryptocurrencyDataRepository->fetchData();
        
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        
        return $this->render('cryptocurrency/index.html.twig', [
            'cryptocurrencies' => $pagination,
        ]);
    }

    /**
     * @Route("/cryptocurrencies/watchlist", name="app_cryptocurrenciesWatchlist")
     */
    public function showWatchlist(PaginatorInterface $paginator, Request $request, CryptocurrencyDataRepository $cryptocurrencyDataRepository)
    {
        $user = $this->getUser();
        
        $watchlist = $user->getWatchlist()->getValues();
        
        foreach ($watchlist as $value) {
            $ids[] = $value->getId();
        }
        
        $arrayToString = implode(",", $ids);

        $query = $cryptocurrencyDataRepository->fetchDataByIds($arrayToString);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('components/table_watchlist.html.twig', [
            'cryptocurrencies' => $pagination,
        ]);
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
     * @Route("/addWatchlist/{id}", name="app_watchlist", methods={"GET"})
     */
    public function watchlistManager(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $cryptocurrency = $entityManager->getRepository(Cryptocurrencies::class)->find($id);
        
        if (!$user) {

            return $this->json(['code' => 403, 'message' => 'Connectez-vous'], 403);
        
        } elseif ($user) {

            if ($user->getWatchlist()->getValues() == null) {

                $user->addWatchlist($cryptocurrency);
                $entityManager->flush();

                return $this->json(['message' => 'add'], 200);

            } else {

                if ($cryptocurrency->likedByUser($user)) {  //si l'utilisateur a déjà ajouté cette cryptomonnaie à sa watchtlist on la supprime.
                    $user->removeWatchlist($cryptocurrency);
                    $entityManager->flush();
        
                    return $this->json(['message' => 'remove'], 200);
                    
                } else {
                    
                    $user->addWatchlist($cryptocurrency);
                    $entityManager->flush();
                    
                    return $this->json(['message' => 'add'], 200);
                }  
            }
        } 
    }

    /**
     *@Route("/cryptocurrencies/refresh", name="app_cryptocurrenciesRefresh", methods={"GET"})
     */
    public function updateByUser(CryptocurrencyDataRepository $cryptocurrencyDataRepository, Request $request, CallApi $callApi, PaginatorInterface $paginator, EntityManagerInterface $em)
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
    
            $query = $cryptocurrencyDataRepository->fetchData();

        } else {

            $query = $cryptocurrencyDataRepository->fetchData();
        }
        
        $cryptocurrencies = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        $contents = $this->render('components/table_rank.html.twig', ['cryptocurrencies' => $cryptocurrencies,])->getContent();
        
        return new JsonResponse($contents);
    
    }
}
