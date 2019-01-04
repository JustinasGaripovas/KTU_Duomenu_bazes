<?php

namespace App\Security\Voter;

use App\Entity\Job;
use App\Entity\LdapUser;
use App\Entity\Mechanism;
use App\Entity\RoadSection;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminBlockVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['VIEW', 'EDIT', 'DELETE'])
            && ($subject instanceof Job || $subject instanceof LdapUser || $subject instanceof Mechanism || $subject instanceof RoadSection);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($this->security->isGranted('ADMIN'))
        {
            return true;
        }

        if ($this->security->isGranted('SUPER_VIEWER'))
        {
            if($attribute == 'SHOW')
            {
                return true;
            }else{
                return false;
            }
        }

        return false;
    }

}
