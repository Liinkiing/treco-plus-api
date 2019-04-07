<?php

namespace App\GraphQL\Resolver\Query;

use App\Entity\User;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class ViewerTeamsResolver implements ResolverInterface
{
    public function __invoke(Argument $args, User $user)
    {
        return $user->getTeams();
    }
}
