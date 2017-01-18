<?php
namespace RQL\Filters;
use RQL\Lexer;

use RQL\RQLFilter;

class CardFilter extends RQLFilter
{
	
	private $columnName;
	
	private $card = NULL;
	
	public function parse(\RQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
	}
	
	public function getSql(){
		
		$default = '';

		if (isset($_REQUEST['filter:card:'.$this->columnName]) && !empty($_REQUEST['filter:card:'.$this->columnName])) {
				
			$card = $_REQUEST['filter:card:'.$this->columnName];
			$this->card = $card;
		}

		$result =  isset($card) ? $card : $default;

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
						<input type='text' id='".$this->columnName."' class='form-control card' name='filter:card:".$this->columnName."' value='".$this->card."' title='0-9/X' pattern='[0-9X]+'  placeholder='".strtolower($this->getFilterLabel())."'>
				</div>
				",
				"
				<input type='hidden' class='card' name='filter:card:".$this->columnName."' value='".$this->card."' >
				");
	}
	
	public function getFilterValue(){
		return "&nbsp;".$this->card;
	}
}