<?php
/**
 * Created by PhpStorm.
 * User: michi
 * Date: 28.12.16
 * Time: 19:37
 */


namespace AppBundle\Entity;

use AppBundle\UserType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints as RollerworksPassword;

/**
 * AppBundle\Entity\User
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="enumusertype")
     */
    protected $userType = UserType::USER_TYPE_USER;
    
    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $name = '';

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="users_orgas",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="organization_id", referencedColumnName="id")}
     *      )
     */
    private $organizations;

    /**
     * @RollerworksPassword\PasswordRequirements(requireLetters=true, requireNumbers=true)
     */
    protected $password;

    /**
     * @RollerworksPassword\PasswordRequirements(requireLetters=true, requireNumbers=true)
     */
    protected $plainPassword;


    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->organizations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setEmail($email)
    {
        parent::setUsername($email);
        parent::setUsernameCanonical($email);
        return parent::setEmail($email);
    }


    public function getAllowedPoolPaths()
    {
        $r = array();
        $r[] = 'user-' . $this->id;

        $orgas = $this->getOrganizations();
        foreach ($orgas as $orga) { /** @var User $orga */
            $r[] = 'orga-' . $orga->getId();
        }

        return $r;
    }

    public function isPoolFileAllowed($path)
    {
        $file = ltrim($path, "/\r\n\t ");
        $base = substr($file, 0, strpos($file, '/'));
        return (in_array($base, $this->getAllowedPoolPaths()));
    }


    public function isPresentationAllowed(Presentation $presentation)
    {
        if ($presentation->getOwner() == $this) {
            return true;
        }

        $orgas = $this->getOrganizations();
        foreach ($orgas as $orga) { /** @var User $orga */
            if ($presentation->getOwner() == $orga) {
                return true;
            }
        }

        return false;
    }


    public function isSlideAllowed(Slide $slide)
    {
        return $this->isPresentationAllowed($slide->getPresentation());
    }


    public function getPresentations(EntityManager $em)
    {
        $user = $this;
        $rep = $em->getRepository('AppBundle:Presentation');
        $pres = array();

        $pres_user = $rep->findBy(array('owner' => $user));

        foreach ($pres_user as $p) {
            $pres['me'][] = $p;
        }

        $orgas = $user->getOrganizations();
        foreach ($orgas as $orga) { /** @var User $orga */
            $pres_orga = $rep->findBy(array('owner' => $orga));
            foreach ($pres_orga as $p) {
                $pres[$orga->getName()][] = $p;
            }
        }

        return $pres;
    }


    public static function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    /**
     * Set userType
     *
     * @param enumusertype $userType
     *
     * @return User
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get userType
     *
     * @return enumusertype
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Add organization
     *
     * @param \AppBundle\Entity\User $organization
     *
     * @return User
     */
    public function addOrganization(\AppBundle\Entity\User $organization)
    {
        $this->organizations[] = $organization;

        return $this;
    }

    /**
     * Remove organization
     *
     * @param \AppBundle\Entity\User $organization
     */
    public function removeOrganization(\AppBundle\Entity\User $organization)
    {
        $this->organizations->removeElement($organization);
    }

    /**
     * Get organizations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
