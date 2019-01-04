<?php

namespace App\Security\Voter;

use App\Entity\InsuredEvent;
use App\Repository\LdapUserRepository;
use App\Repository\SubunitRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class InsuredEventVoter extends Voter
{
    private $ldapUserRepository;
    private $security;
    private $subunitRepository;

    public function __construct(LdapUserRepository $ldapUserRepository, Security $security,SubunitRepository $subunitRepository)
    {
        $this->subunitRepository = $subunitRepository;
        $this->security = $security;
        $this->ldapUserRepository = $ldapUserRepository;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['SHOW','EDIT','DELETE'])
            && $subject instanceof InsuredEvent;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        $ldapUser = $this->ldapUserRepository->findUnitIdByUserName($user->getUsername());

        $subunitName = $this->ldapUserRepository->findUnitIdByUserName($user->getUsername())->getSubunit()->getName();

        if ($this->security->isGranted("WORKER", $ldapUser)) {
            if ($subject->getSubunit() == $subunitName)
            {
                return $this->switchCase($attribute,$ldapUser->getInsuredEvent());
            }
        }

        if($this->security->isGranted("ADMIN", $ldapUser) || $this->security->isGranted("SUPER_VIEWER", $ldapUser))
        {
            return $this->switchCase($attribute,$ldapUser->getInsuredEvent());
        }

        if($this->security->isGranted("UNIT_VIEWER", $ldapUser) || $this->security->isGranted("SUPER_MASTER", $ldapUser) || $this->security->isGranted("ROAD_MASTER", $ldapUser)) {
            if ($subject->getSubunit() == $subunitName) {
                return $this->switchCase($attribute, $ldapUser->getInsuredEvent());
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
