<?php
namespace RQL;
 
class RQLExecuter
{
	private $tql;
	
	private $sql;
	
	private $formatters;
	
	private $db;
	
	private $result;
	
	private $sqlTokens;

	private $resultFilters = array();
	
	public function __construct($tql, &$db){
		$this->tql = $tql;
		$this->db = $db;
		$this->_parse();
	}
	
	private function execute($filters){
		$this->parseInlineFilters($filters);
		// replacing null values from sql
		$this->sql = preg_replace("/(and\s[a-zA-Z0-9\.\_]+\s?\=\s?\'\')/", '', $this->sql);
		$this->sql = preg_replace("/(where\s[a-zA-Z0-9\.\_]+\s?\=\s?\'\')/", 'where 1=1', $this->sql);

		$this->sql = preg_replace("/(and\s[a-zA-Z0-9\.\_]+\s?like\s?\'\')/", '', $this->sql);

		// status = '14' => expire_date <= date('Y-m-d')
		$this->sql = preg_replace("/(status\s?\=\s?\'14\')/", "expire_date <= '".date('Y-m-d')."'", $this->sql);

		$this->parseInlineFilters($filters);
		// show_pre($this->sql); die;
		$this->result = $this->db->query($this->sql)->result_array();

	}
	
	public function getSQL(){
		return $this->sql;
	}
	
	private function parseInlineFilters($repalcement){
	
	//	preg_match_all('/filter:[a-z]+:[a-z\.]+:[a-z]+/s',$this->sql,$filters,PREG_OFFSET_CAPTURE,TRUE);
	
		preg_match_all('/filter:[a-z]+:[a-zA-Z0-9.-_]+:[a-z]+:[a-zA-Z0-9]+/s', $this->sql, $orFilters, PREG_OFFSET_CAPTURE, TRUE);
		
		if(count($orFilters[0]) > 0){
			foreach($orFilters[0] as $f){
				$name = $f[0];
				$offset = $f[1];
				
				$filterParser = new Parser($name);
		
				$result = $filterParser->parse();
				$filter = $result->getFilter();
				$replacement = $filter->getSql();
				$this->resultFilters[] = $filter;
		
				$this->sql = str_replace($name, $replacement, $this->sql);
			}
		}
		
		preg_match_all('/filter:[a-z]+:[a-zA-Z0-9]+/s',$this->sql,$filters,PREG_OFFSET_CAPTURE,TRUE);
		
		if(count($filters[0]) > 0){
			foreach($filters[0] as $f){
				$name = $f[0];
				$offset = $f[1];
				
				$filterParser = new Parser($name);

				$result = $filterParser->parse();	
				$filter = $result->getFilter();
				$replacement = $filter->getSql();
				$this->resultFilters[] = $filter;
				
				$this->sql = str_replace($name,$replacement,$this->sql);
			}
		}
		
		
	}
	
	private function _parse(){
		preg_match('/\[(.*?)\]/s', $this->tql,$modifier);
		
		if(count($modifier) > 0){
			$text_match = $modifier[0];
			$tql = $modifier[1];

			$this->sql = strstr($this->tql, $text_match,TRUE);
			
			$parser = new Parser($tql);
		
			$this->formatters = $parser->parse();
		}else{
			$this->sql = $this->tql;
		}
	}
	
	public function getResult($filters = NULL){
		$this->execute($filters);
		$output = array();
		$output['result'] = array();
		$output['rowCount'] = count($this->result);
		
		$tablizerColumns = $this->formatters ? $this->formatters->getTablizerColumns():array();
		$aggregators = $this->formatters ? $this->formatters->getAggregateColumns():array();
		
		if(count($tablizerColumns) > 0){
			$tablizer_column = $tablizerColumns[0];
			$output['tablized'] = TRUE;
			
			foreach($this->result as $r){
				if(isset($r[$tablizer_column])){
					$column = $r[$tablizer_column];
					unset($r[$tablizer_column]);
					if(!isset($output['result'][$column])){
						$output['result'][$column] = array();
						$output['result'][$column]['rowCount'] = 0;
						$output['result'][$column]['transactions'] = array();
					}
					
					$output['result'][$column]['transactions'][] = $r;
					
					if(count($aggregators) > 0){
						foreach($aggregators as $a){
							$a->processRow($r,$output['result'][$column]);
						}
					}
					
					$output['result'][$column]['rowCount']++;
				}
			}
		}else{
			$output['transactions'] = $this->result;
			$output['tablized'] = FALSE;
		}
		
		if(count($aggregators) > 0){
			foreach($aggregators as $a){
				$output['aggregates'][$a->getField()][$a->getTypeString()] = $a->getResult();
			}
		}
// 		show_pre($output);
		return $output;
	}
	

	public function getResultFilters()
	{
	    return $this->resultFilters;
	}

}