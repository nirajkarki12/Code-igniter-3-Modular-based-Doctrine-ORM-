<?php
namespace models\Repository;
 
use Doctrine\ORM\Query\AST\WhereClause;

use Doctrine\ORM\EntityRepository;
use content\models\Content,
	Doctrine\ORM\Query;
 
class GroupRepository extends EntityRepository{
	
	public function getGroupList(){
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('g')
			->from('models\Group', 'g')
			->groupBy('g.id');

		return $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);		
	}

	public function getGroupListPaginate($filters = NULL){
		
		$qb = $this->_em->createQueryBuilder();
		$qb->select('g')
		->from('models\Group', 'g')
		->where('1=1')
		// ->andWhere('u.id = :Id')->setParameter('Id', \Current_User::user()->id());
		;
		if(is_array($filters) and count($filters) > 0)
		{			
			if(isset($filters['group_status']) and $filters['group_status'] != "")
			{
				$status = $filters['group_status'];			
				$qb->andWhere('g.status = :status')->setParameter('status', $status);
			}else{
				$qb->andWhere('g.status != :delStatus')->setParameter('delStatus', \models\Group::STATUS_DELETE);
			}

			if(isset($filters['name']) and $filters['name']!="")
			{
				$qb->andWhere('g.name = :groupName')->setParameter('groupName', $filters['name']);
			}
		}else{
			$qb->andWhere('g.status != :delStatus')->setParameter('delStatus', \models\Group::STATUS_DELETE);
		}
		$qb->orderBy('g.name','asc');

		$query = $qb->getQuery();
		
		return $query;
	}

	public function getActiveGroup($name){
		$qb = $this->_em->createQueryBuilder();
		$qb->select('g')
			->from('models\Group', 'g')
			->Where('1=1')
			->andWhere('g.status != :status')
			->setParameter('status',  \models\Group::STATUS_DELETE)
			->andWhere('g.name = :name')
			->setParameter('name',  $name);

		 return $qb->getQuery()->getOneOrNullResult();
	}

	public function getGroupLists(){
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select(array('g.id AS group_id', 'g.name', 'g.description'))
			->from('models\Group', 'g')
		//	->leftJoin('g.users', 'u')
			// ->groupBy('g.id')
		//	->where('u.deleted = 0')
			;
		
		return $qb->getQuery()->getResult();
		
	}

	public function getUserGroup($user_id){
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('g')
			->from('models\Group', 'g')
			->leftJoin('g.users','u')
			;

		$qb->Where('u.id = :userId')->setParameter('userId', $user_id);

		try {
	        return $qb->getQuery()->getResult(); 
	    }catch(\Doctrine\ORM\NoResultException $e) {
	        return null;
    	}

		// return $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);		
	}
	public function getPermissions()
	{
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select(array('p.id AS perm_id', 'p.name', 'p.description'))
			->from('models\Permissions', 'p')
			->leftJoin('p.groups', 'g');
			// ->groupBy('p.id');
		
		return $qb->getQuery()->getResult();	
	}
}