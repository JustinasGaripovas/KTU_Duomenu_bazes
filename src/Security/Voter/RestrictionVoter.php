<?php

namespace App\Security\Voter;

use App\Entity\Restriction;
use App\Repository\LdapUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class RestrictionVoter extends Voter
{
    private $ldapUserRepository;
    private $security;

    public function __construct(LdapUserRepository $ldapUserRepository, Security $security)
    {
        $this->security = $security;
        $this->ldapUserRepository = $ldapUserRepository;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['SHOW','EDIT','DELETE'])
            && $subject instanceof Restriction;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        $ldapUser = $this->ldapUserRepository->findUnitIdByUserName($user->getUsername());

        $subunitName = $this->ldapUserRepository->findUnitIdByUserName($user->getUsername())->getSubunit()->getName();

        if($this->security->isGranted("ADMIN", $ldapUser) || $this->security->isGranted("SUPER_VIEWER", $ldapUser))
        {
            return $this->switchCase($attribute,$ldapUser->getRestrictions());
        }

        if($this->security->isGranted("WORKER", $ldapUser) || $this->security->isGranted("UNIT_VIEWER", $ldapUser) || $this->security->isGranted("SUPER_MASTER", $ldapUser) || $this->security->isGranted("ROAD_MASTER", $ldapUser)) {
            if ($subunitName == $subject->getSubunit()) {
                return $this->switchCase($attribute, $ldapUser->getRestrictions());
            }
        }

        return false;
    }

    private function switchCase($attribute,$level)
    {
        switch ($attribute) {
            case 'SHOW':
                if ($level >= 1) {
                    return true;
                }
                break;
            case 'EDIT':
                if ($level >= 2) {
                    return true;
                }
                break;
            case 'DELETE':
                if ($level >= 3) {
                    return true;
                }
                break;
        }

        return false;
    }
}
