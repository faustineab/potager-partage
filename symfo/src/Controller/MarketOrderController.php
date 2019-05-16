<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Entity\Vegetable;
use App\Entity\MarketOffer;
use App\Entity\MarketOrder;
use App\Repository\VegetableRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MarketOfferRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



/**
 * @Route("/api/garden")
 */
class MarketOrderController extends AbstractController
{

/**
     * @Route("/{gardenid}/marketoffer/{offerid}/marketorder/new", name="market_order_new", methods={"POST"})
     * @ParamConverter("garden", options={"id" = "gardenid"})
     * @ParamConverter("market_offer", options={"id" = "offerid"})
     */
    public function new(MarketOffer $marketOffer, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $content = $request->getContent();

        $marketOrder = $serializer->deserialize($content, MarketOrder::class, 'json');

        $errors = $validator->validate($marketOrder);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                return JsonResponse::fromJsonString(
                    'message: Votre réponse comporte des erreurs : ' . $error . '.',
                    406
                );
            }
        }

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $marketOrder->setMarketOffer($marketOffer);
        $marketOrder->setUser($currentUser);

        $entityManager->persist($marketOrder);
        $entityManager->flush();

        return JsonResponse::fromJsonString('message: Votre order a été enregistré', 200);
    }


}