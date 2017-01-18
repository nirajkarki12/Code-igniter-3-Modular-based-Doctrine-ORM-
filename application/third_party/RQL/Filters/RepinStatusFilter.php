<?php
namespace RQL\Filters;
use RQL\Lexer;

use RQL\RQLFilter;

class RepinStatusFilter extends RQLFilter
{
	
	private $columnName;
	
	private $repinstatus = NULL;
	
	public function parse(\RQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
	}
	
	public function getSql(){
		
		$default = '';

		if (isset($_REQUEST['filter:repinstatus:'.$this->columnName]) && !empty($_REQUEST['filter:repinstatus:'.$this->columnName])) {
				
			$repinstatus = $_REQUEST['filter:repinstatus:'.$this->columnName];
			$this->repinstatus = $repinstatus;
		}

		$result =  isset($repinstatus) ? $repinstatus : $default;

		return  "'".$result."'";
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
		$result = '';
		foreach (\models\Repin::$status_types as $key => $value) {
			$sel = $key == $this->repinstatus ? 'selected' : '';
			$result.= "<option value={$key} {$sel}>{$value}</option>";
			
		}

		return array("
				<div class='col-xs-12 col-md-4 form-group'>
					<label for='".$this->columnName."'>".$this->getFilterLabel()."</label>
						<select id='".$this->columnName."' class='form-control repinstatus' name='filter:repinstatus:".$this->columnName."'>
							<option value=''> --- Select Status --- </option>".
							$result
						."</select>
				</div>
				",
				"
				<input type='hidden' class='repinstatus' name='filter:repinstatus:".$this->columnName."' value='".$this->repinstatus."' >
				");
	}
	
	public function getFilterValue(){
		return "&nbsp;".$this->repinstatus;
	}
}