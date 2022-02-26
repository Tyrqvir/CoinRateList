<?php

declare(strict_types=1);

namespace App\Rate\Action\V1;

use App\Shared\Message\Query\RatesByCriteriaQuery;
use App\Shared\MessageBus\QueryBusTrait;
use App\Shared\Model\Http\ApiResponseBody;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class ListAction extends AbstractController
{
    use QueryBusTrait;

    /**
     * @Route("/rates/coins/{coin}/currensies/{currency}/start/{start}/end/{end}", name="rate_list_action", methods={"GET"})
     */
    public function __invoke(string $coin, string $currency, int $start, int $end): Response
    {
        $result = [];
        $envelope = $this->query(new RatesByCriteriaQuery($coin, $currency, $start, $end));

        if (null !== $handledStamp = $envelope->last(HandledStamp::class)) {
            $result = $handledStamp->getResult();
        }

        return $this->json(new ApiResponseBody($result));
    }
}
