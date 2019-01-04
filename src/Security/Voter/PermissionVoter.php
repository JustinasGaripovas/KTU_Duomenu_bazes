<?php
namespace App\Security\Voter;
use App\Repository\LdapUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
class PermissionVoter extends Voter
{
    private $ldapUserRepository;
    private $security;

    const Inspection = 'INSPECTION';
    const DoneJobs = 'DONE_JOBS';
    const Restriction = 'RESTRICTIONS';
    const Insured = 'INSURED';
    const Reports = 'REPORTS';
    const Winter = 'WINTER';
    const Flood = "FLOOD";
    const ContractJobs = "CONTRACT_JOBS";

    public function __construct(LdapUserRepository $ldapUserRepository,Security $security)
    {
        $this->security = $security;
        $this->ldapUserRepository = $ldapUserRepository;
    }
    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [
            self::Inspection,
            self::DoneJobs,
            self::Restriction,
            self::Insured,
            self::Reports,
            self::Winter,
            self::Flood,
            self::ContractJobs
        ]);
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        $ldap = $this->ldapUserRepository->findUnitIdByUserName($user->getUserName());


        switch ($attribute)
        {
            case self::Inspection:
                return 0 != $ldap->getInspection();
                break;
            case self::DoneJobs:
                return 0 != $ldap->getDoneJobs();
                break;
            case self::Restriction:
                return 0 != $ldap->getRestrictions();
                break;
            case self::Insured:
                return 0 != $ldap->getInsuredEvent();
                break;
            case self::Reports:
                return 0 != $ldap->getReports();
                break;
            case self::Winter:
                return 0 != $ldap->getWinter();
                break;
            case self::Flood:
                return 0 != $ldap->getFlood();
                break;
            case self::ContractJobs:
                return 0 != $ldap->getContractJobs();
                break;
        }
        return false;
    }
    
}