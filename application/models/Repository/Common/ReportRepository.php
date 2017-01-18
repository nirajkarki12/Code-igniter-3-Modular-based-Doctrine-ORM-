<?php
namespace models\Repository\Common;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
 
class ReportRepository extends EntityRepository{
	
	public function getReports($offset = NULL,$perpage = NULL){
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select(array('r.id','r.description','r.sqlquery','r.name','r.slug'))
			->from('models\Common\Report','r');
		
		if(!is_null($offset))
			$qb->setFirstResult($offset);
		
		if(!is_null($perpage))
			$qb->setMaxResults($perpage);
		
		$paginator = new Paginator($qb->getQuery(), $fetchJoin = true);
		return $paginator;
	}
	
	public function getReportGroups($filters = array()) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select(array('rg.id','rg.name','rg.slug'))
			->from('models\Common\ReportGroup','rg')
			->where("1 = 1")
			;

		if (count($filters) > 0) {
			foreach($filters as $k => $v)
				$qb->andWhere("rg.".$k."='".$v."'");
		}
			
		return $qb->getQuery()->getResult();
		
	}

}