<?php

namespace App\Security\Voter;

use App\Entity\Inspection;
use App\Repository\LdapUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class InspectionVoter extends Voter
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
            && $subject instanceof Inspection;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        $ldapUser = $this->ldapUserRepository->findUnitIdByUserName($user->getUsername());

        if ($this->security->isGranted("WORKER")) {
            if ($ldapUser->getName() == $subject->getUsername()) {
                return $this->switchCase($attribute,$ldapUser->getInspection());
            }
        }

        if($this->security->isGranted("ADMIN") || $this->security->isGranted("SUPER_VIEWER"))
        {
            return $this->switchCase($attribute,$ldapUser->getInspection());
        }

        if($this->security->isGranted("UNIT_VIEWER") || $this->security->isGranted("SUPER_MASTER") || $this->security->isGranted("ROAD_MASTER")) {
            if ($ldapUser->getSubunit()->getId() == $subject->getSubUnitId()) {
                return $this->switchCase($attribute, $ldapUser->getInspection());
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
