<?php

namespace App\Security\Voter;

use App\Repository\LdapUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RoleVoter extends Voter
{
    const admin        = 'ADMIN';
    const super_viewer = 'SUPER_VIEWER';
    const unit_viewer  = 'UNIT_VIEWER';
    const super_master = 'SUPER_MASTER';
    const road_master  = 'ROAD_MASTER';
    const worker       = 'WORKER';

    private $ldapUserRepository;

    public function __construct(LdapUserRepository $ldapUserRepository)
    {
        $this->ldapUserRepository = $ldapUserRepository;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::admin, self::super_viewer,self::unit_viewer,self::super_master,self::road_master,self::worker])
            && $subject == null;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        //Ldap role
        $role = $this->ldapUserRepository->findUnitIdByUserName($user->getUsername())->getRole();
        // ... (check conditions and return true to grant permission) ...

        switch ($attribute) {
            case self::admin:
                return (self::admin == $role);
                break;
            case self::super_viewer:
                return (self::super_viewer == $role);
                break;
            case self::unit_viewer:
                return (self::unit_viewer == $role);
                break;
            case self::super_master:
                return (self::super_master == $role);
                break;
            case self::road_master:
                return (self::road_master == $role);
                break;
            case self::worker:
                return (self::worker == $role);
                break;
        }

        return false;
    }
}
