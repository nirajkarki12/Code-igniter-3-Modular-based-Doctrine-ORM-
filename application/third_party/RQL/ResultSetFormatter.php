<?php

namespace RQL;

class ResultSetFormatter
{
	private $filterableColumns = array();
	
	private $filter;
	
	private $tablizerColumns = array();
	
	private $aggregateColumns = array();
	
	public function addTabelizerColumn($column){
		$this->tablizerColumns[] = $column;
	}
	
	
	
	public function getTablizerColumns(){
		return $this->tablizerColumns;
	}
	
	public function addFilterableColumn($column){
		$this->filterableColumns[] = $column;
	}
	
	public function getFilterableColumns(){
		return $this->filterableColumns;
	}
	
	public function addAggregateColumn($column){
		$this->aggregateColumns[] = $column;
	}
	
	public function getAggregateColumns(){
		return $this->aggregateColumns;
	}

	public function getFilter()
	{
	    return $this->filter;
	}

	public function setFilter($filter)
	{
	    $this->filter = $filter;
	}
}

