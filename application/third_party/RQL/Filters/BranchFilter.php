<?php
namespace RQL\Filters;
use RQL\Lexer;

use RQL\RQLFilter;

class BranchFilter extends RQLFilter
{
	
	private $columnName;
	
	private $branch = NULL;
	
	public function parse(\RQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
	}
	
	public function getSql(){
		
		$default = '';

		if (isset($_REQUEST['filter:branch:'.$this->columnName]) && !empty($_REQUEST['filter:branch:'.$this->columnName])) {
				
			$branch = $_REQUEST['filter:branch:'.$this->columnName];
			$this->branch = $branch;
		}

		$result =  isset($branch) ? $branch : $default;

		return  "'".$result."'";
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
		$result = '';
		$branch =  \CI::$APP->doctrine->em->getRepository('models\Branch')->getActiveBranch();
 		// $branch = $this->doctrine->em->getRepository('models\Branch')->getActiveBranch();
		foreach ($branch as $value) {
			$sel = $value->getBranchId() == $this->branch ? 'selected' : '';
			$result.= "<option value={$value->getBranchId()} {$sel}>{$value->getBranchName()}</option>";
			
		}

		return array("
				<div class='col-xs-12 col-md-4 form-group'>
					<label for='".$this->columnName."'>".$this->getFilterLabel()."</label>
						<select id='".$this->columnName."' class='form-control branch' name='filter:branch:".$this->columnName."'>
							<option value=''> --- Select Status --- </option>".
							$result
						."</select>
				</div>
				",
				"
				<input type='hidden' class='branch' name='filter:branch:".$this->columnName."' value='".$this->branch."' >
				");
	}
	
	public function getFilterValue(){
		return "&nbsp;".$this->branch;
	}
}