<?php
namespace models;
use Gedmo\Mapping\Annotation as Gedmo,
	Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection; 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="models\Repository\UserRepository")
 * @ORM\Table(name="tbl_user")
 */
class User
{
    const SUPER_ADMIN = 1;

	const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 2;
	const STATUS_DELETE = 3;

	public static $status_types = array(
			self::STATUS_ACTIVE	=> 'Active',
			self::STATUS_BLOCK =>'Block',
			self::STATUS_DELETE =>'Deleted',
		);

	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
     */
    private $id;
	
    /**
     * @ORM\Column(type="string",length=255,unique=false)
     */
    private $username;
    
    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $password;

    /**
    * @ORM\Column(type="string")
    */
    private $firstname;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    private $middlename;

    /**
    * @ORM\Column(type="string")
    */
    private $lastname;

    /**
    * @ORM\Column(type="string", length=255, unique=false)
    */
    private $email;

    /**
    * @ORM\Column(name="is_admin", type="smallint", nullable=false)
    */
    private $isAdmin = 0;

    /**
    *@Gedmo\Timestampable(on="create")
    *@ORM\Column(name="createdate",type="datetime")
    */
    private $createdAt;

    /**
    * @Gedmo\Timestampable(on="update")
    * @ORM\Column(type="datetime", nullable=true, name="updated_at")
    */
    private $updatedAt;

    /**
    * @Gedmo\Timestampable(on="update")
    * @ORM\Column(name="logindate",type="datetime", nullable=true)
    */
    private $lastLogged;

    /**
    *@ORM\Column(type="smallint", nullable=true)
    */
    private $status = self::STATUS_ACTIVE;

    /**
    *@ORM\Column(type="smallint")
    */
    private $first_login = 1;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    private $token;

    /**
    * @ORM\ManyToMany(targetEntity="\models\Group", inversedBy="users")
    * @ORM\JoinTable(name="tbl_user_groups")
    **/
    private $groups;

    public function __construct() {
        $this->groups = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getFirstName(){
        return $this->firstname;
    }

    public function setFirstName($firstname){
        return $this->firstname=$firstname;
    }

    public function getMiddleName(){
        return $this->middlename;
    }

    public function setMiddleName($middlename){
        return $this->middlename=$middlename;
    }

    public function getLastName(){
        return $this->lastname;
    }

    public function setLastName($lastname){
        return $this->lastname=$lastname;
    }

    public function getName()
    {
        $fname = ($this->firstname)? $this->firstname." " : '';
        $mname = ($this->middlename)? $this->middlename." ":'';
        $lname = ($this->lastname)? $this->lastname : '';

        return $fname.$mname.$lname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setIsAdmin($bool)
    {
        $this->isAdmin = $bool;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

     public function setCreatedAt($date)
    {
       $this->createdAt = $date;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setUpdatedAt($date)
    {
       $this->updatedAt = $date;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setLastLogged(){
        $this->lastLogged = new \DateTime();
    }
    
    public function getLastLogged(){
        return $this->lastLogged;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getFirstLogin()
    {
        return $this->first_login;
    }

    public function setFirstLogin($first_login)
    {
        $this->first_login = $first_login;
    }

    public function initGroups()
    {
        return new ArrayCollection();
    }    

    public function getGroups(){
        return $this->groups;
    }

    public function setGroups(ArrayCollection $groups)
    {
        $this->groups = $groups;
    }

    public function addGroup(\models\Group $group){
        $group->addUser($this);
        $this->groups[] = $group;
    }

    public function removeGroup(\models\Group $group){
        $this->groups->removeElement($group);
    }

    // checking user status
    public function isActive()
    {
        return ($this->status == self::STATUS_ACTIVE)?true:false;
    }

    public function isBlocked()
    {
        return ($this->status == self::STATUS_BLOCK)?true:false;
    }

    public function isDeleted()
    {
        return ($this->status == self::STATUS_DELETE) ? true : false;
    }

    public function activate()
    {
        $this->status = self::STATUS_ACTIVE;
    }
    
    public function deactivate(){
        $this->status = self::STATUS_BLOCK;
    }
    
    public function delete()
    {
        $this->status = self::STATUS_DELETE;
    }

    public function isSuperAdmin()
    {
        return ($this->isAdmin == self::SUPER_ADMIN) ? true : false;
    }

    public function isFirstLogin()
    {
        return ($this->first_login == 1) ? true : false;
    }
}


