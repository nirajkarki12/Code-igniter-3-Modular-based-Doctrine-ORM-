<?php
namespace RQL\Filters;
use RQL\Lexer;

use RQL\RQLFilter;

class BranchCodeFilter extends RQLFilter
{
	
	private $columnName;
	
	private $branchcode = NULL;
	
	public function parse(\RQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
	}
	
	public function getSql(){
		
		$default = '';

		if (isset($_REQUEST['filter:branchcode:'.$this->columnName]) && !empty($_REQUEST['filter:branchcode:'.$this->columnName])) {
				
			$branchcode = $_REQUEST['filter:branchcode:'.$this->columnName];
			$this->branchcode = $branchcode;
		}

		$result =  isset($branchcode) ? $branchcode : $default;

		return  "'".$result."'";
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
		return array("
				<div class='col-xs-12 col-md-4 form-group'>
					<label for='".$this->columnName."'>".
						$this->getFilterLabel()."</label>
						<input type='text' id='".$this->columnName."' class='form-control branchcode' name='filter:branchcode:".$this->columnName."' value='".$this->branchcode."' title='A-Z/0-9' pattern='[0-9A-Z]+' placeholder='".strtolower($this->getFilterLabel())."'>
				</div>
				",
				"
				<input type='hidden' class='branchcode' name='filter:branchcode:".$this->columnName."' value='".$this->branchcode."' >
				");
	}
	
	public function getFilterValue(){
		return "&nbsp;".$this->branchcode;
	}
}