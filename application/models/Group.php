<?php
namespace models;
use Gedmo\Mapping\Annotation as Gedmo,
	Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection; 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @ORM\Entity(repositoryClass="models\Repository\GroupRepository")
 * @ORM\Table(name="tbl_group")
 */
class Group
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
    * @ORM\Column(type="string",length=255)
    */
    private $name;

    /**
    * @ORM\Column(type="string",length=255, nullable=true)
    */
    private $description;

    /**
    *@Gedmo\Timestampable(on="create")
    *@ORM\Column(name="createdAt", type="datetime")
    */
    private $createdAt;

    /**
    **@Gedmo\Timestampable(on="update")
    *@ORM\Column(name="updatedAt", type="datetime")
    */
    private $updatedAt;

    /**
    * @ORM\Column(name="is_admin", type="smallint", nullable=false)
    */
    private $isAdmin = 0;

    /**
    * @ORM\Column(type="smallint",length=255)
    */
    private $status = self::STATUS_ACTIVE;

    /**
    * @Gedmo\Slug(separator="_", updatable=true, fields={"name"})
    * @ORM\Column(type="string", length=255, unique=true, nullable=false)
    */
    private $slug;

	/**
	* @ORM\ManyToMany(targetEntity="\models\Permissions", inversedBy="groups")
	* @ORM\JoinTable(name="tbl_groups_permissions")
	**/
	private $permissions;

	/**
     * @ORM\ManyToMany(targetEntity="\models\User", mappedBy="groups")
     **/
    private $users;

	public function __construct() {
        $this->permissions = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($desc)
    {
        $this->description = $desc;
    }

    public function getDescription()
    {
        return $this->description;
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

    public function setIsAdmin($bool)
    {
        $this->isAdmin = $bool;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getPermissions(){
    	return $this->permissions;
    }

    public function setPermissions($permissions)
    {
    	$this->permissions = $permissions;
    }

    public function addPermission(\models\Permissions $permission){
    	$permission->addGroup($this);
    	$this->permissions[] = $permission;
    }

    public function removePermission(\models\Permissions $permission){
		$this->permissions->removeElement($permission);
	}

    public function resetPermissions(){
        $this->permissions = new ArrayCollection();
    }

	public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;
    }

    public function addUser(\models\User $user){
        $this->users[] = $user;
    }

    public function removeUser(\models\User $user){
        $this->users->removeElement($user);
    }

    // checking ygroup status
    public function isActive()
    {
        return ($this->status == self::STATUS_ACTIVE)?true:false;
    }

    public function isAdmin()
    {
        return ($this->isAdmin == self::SUPER_ADMIN)?true:false;
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
}