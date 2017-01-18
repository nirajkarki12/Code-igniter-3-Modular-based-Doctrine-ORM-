<?php
namespace RQL\Filters;
use models\Group;

use RQL\Lexer;

use RQL\RQLFilter;

class FreetextFilter extends RQLFilter
{
	
	private $columnName;
	private $freetext = NULL;
	
	public function parse(\RQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
		
	}
	
	public function getSql(){

		$default = '';
		
		if (isset($_REQUEST['filter:freetext:' . $this->columnName]) && !empty($_REQUEST['filter:freetext:' . $this->columnName])) {
			
			$request = $_REQUEST['filter:freetext:' . $this->columnName];
			$this->freetext = $request;
			
			return "'".$this->freetext ."%'";
			
		} 
		
		$result =  isset($request) ? $request : $default;

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
						<input type='text' id='".$this->columnName."' class='form-control freetext' name='filter:freetext:".$this->columnName."' value='".$this->freetext."' title='A-Z/a-z/0-9/_' pattern='[0-9A-Za-z_]+' placeholder='".strtolower($this->getFilterLabel())."'>
				</div>
				",
				"
				<input type='hidden' class='freetext' name='filter:freetext:".$this->columnName."' value='".$this->freetext."' >
				");
	}
	
	public function getFilterValue(){
		return $this->freetext;
	}
}