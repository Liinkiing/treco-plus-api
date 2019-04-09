<?php

namespace App\GraphQL\Mutation;

use App\Repository\CategoryRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

class ChangeTicketPositionMutation implements MutationInterface
{

    private $ticketRepository;
    private $categoryRepository;
    private $em;

    public function __construct(
        TicketRepository $ticketRepository,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $em
    )
     {
         $this->ticketRepository = $ticketRepository;
         $this->categoryRepository = $categoryRepository;
         $this->em = $em;
     }

    public function __invoke(Argument $args)
    {
        [
            $ticketId,
            $fromCategoryId,
            $toCategoryId,
            $newPosition
        ] = [
            $args->offsetGet('ticketId'),
            $args->offsetGet('fromCategoryId'),
            $args->offsetGet('toCategoryId'),
            $args->offsetGet('newPosition'),
        ];

        $ticket = $this->ticketRepository->find($ticketId);

        if (!$ticket) throw new \RuntimeException('Ticket not found. '. __CLASS__);

        if ($fromCategoryId !== $toCategoryId) {
            $newCategory = $this->categoryRepository->find($toCategoryId);

            $ticket->setCategory($newCategory);
        }

        $ticket->setPosition($newPosition);
        $this->em->flush();

        return ['board' => $ticket->getCategory()->getBoard()];
    }

}
