<?php
namespace RQL\Filters;
use RQL\Lexer;

use RQL\RQLFilter;

class RemarksFilter extends RQLFilter
{
	
	private $columnName;
	
	private $remarks = NULL;
	
	public function parse(\RQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
	}
	
	public function getSql(){
		
		$default = '';

		if (isset($_REQUEST['filter:remarks:'.$this->columnName]) && !empty($_REQUEST['filter:remarks:'.$this->columnName])) {
				
			$remarks = $_REQUEST['filter:remarks:'.$this->columnName];
			$this->remarks = $remarks;
		}

		$result =  isset($remarks) ? $remarks : $default;

		return  "'".$result."'";
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
		$result = '';
		foreach (\models\Card::$remarks_types as $key => $value) {
			$sel = $key == $this->remarks ? 'selected' : '';
			$result.= "<option value={$key} {$sel}>{$value}</option>";
		}

		return array("
				<div class='col-xs-12 col-md-4 form-group'>
					<label for='".$this->columnName."'>".$this->getFilterLabel()."</label>
						<select id='".$this->columnName."' class='form-control remarks' name='filter:remarks:".$this->columnName."'>
							<option value=''> --- Select Status --- </option>".
							$result
						."</select>
				</div>
				",
				"
				<input type='hidden' class='remarks' name='filter:remarks:".$this->columnName."' value='".$this->remarks."' >
				");
	}
	
	public function getFilterValue(){
		return "&nbsp;".$this->remarks;
	}
}