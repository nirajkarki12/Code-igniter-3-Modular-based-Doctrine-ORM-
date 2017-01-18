<?php
namespace models\Repository;

use models\User;

use Doctrine\ORM\EntityRepository;
use content\models\Content,
	Doctrine\ORM\Query;
 
class UserRepository extends EntityRepository{
	public function getUserList($filters = NULL){
		
		$qb = $this->_em->createQueryBuilder();
		$qb->select('u, g')
		->from('models\User', 'u')
		->leftJoin('u.groups', 'g')
		->where('1=1')
		;
		if(is_array($filters) and count($filters) > 0)
		{			
			if(isset($filters['groups']) and $filters['groups']!="")
			{
				$qb->andWhere('g.id = :ugid')->setParameter('ugid', $filters['groups']);
			}
			if(isset($filters['user_status']) and $filters['user_status'] != "")
			{
				$status = $filters['user_status'];			
				$qb->andWhere('u.status = :status')->setParameter('status', $status);
			}else{
				$qb->andWhere('u.status != :delStatus')->setParameter('delStatus', \models\User::STATUS_DELETE);
			}

			if(isset($filters['username']) and $filters['username']!="")
			{
				$qb->andWhere('u.username=:u_name')->setParameter('u_name', $filters['username']);
			}
		}else{
			$qb->andWhere('u.status != :delStatus')->setParameter('delStatus', \models\User::STATUS_DELETE);
		}
		$qb->orderBy('u.username','asc');

		return $qb->getQuery();
	}

	public function getActiveUser($username, $status=NULL){
		$qb = $this->_em->createQueryBuilder();
		$qb->select('u')
			->from('models\User', 'u')
			->Where('1=1')
			->andWhere('u.status != :status')
			->setParameter('status',  \models\User::STATUS_DELETE)
			->andWhere('u.username = :username')
			->setParameter('username',  $username);

		if($status)
		{
			$qb->andWhere('u.status = :customStatus')
			->setParameter('customStatus',  $status);
		}
		
		// show_pre($qb->getQuery()->getSQL()); die;
		
		 return $qb->getQuery()->getOneOrNullResult();
	}
	// form validation
	public function getUserByEmail($email, $userId = NULL){
		$qb = $this->_em->createQueryBuilder();
		$qb->select('u')
			->from('models\User', 'u')
			->Where('1=1')
			->andWhere('u.status != :status')
			->setParameter('status',  \models\User::STATUS_DELETE)
			->andWhere('u.email = :email')
			->setParameter('email',  $email)
		;

		if($userId)
		{
			$qb->andWhere('u.id != :userId')
			->setParameter('userId',  $userId);
		}
		
		 return $qb->getQuery()->getOneOrNullResult();
	}
}