<?php
namespace RQL\Filters;
use RQL\Lexer;

use RQL\RQLFilter;

class CardStatusFilter extends RQLFilter
{
	
	private $columnName;
	
	private $cardstatus = NULL;
	
	public function parse(\RQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
	}
	
	public function getSql(){
		
		$default = '';

		if (isset($_REQUEST['filter:cardstatus:'.$this->columnName]) && !empty($_REQUEST['filter:cardstatus:'.$this->columnName])) {
				
			$cardstatus = $_REQUEST['filter:cardstatus:'.$this->columnName];
			$this->cardstatus = $cardstatus;
		}

		$result =  isset($cardstatus) ? $cardstatus : $default;

		return  "'".$result."'";
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
		$result = '';
		foreach (\models\Card::status_types() as $key => $value) {
			if($key != \models\Card::STATUS_INACTIVE)
			{
				$sel = $key == $this->cardstatus ? 'selected' : '';
				$result.= "<option value={$key} {$sel}>{$value}</option>";
			}
			
		}

		return array("
				<div class='col-xs-12 col-md-4 form-group'>
					<label for='".$this->columnName."'>".$this->getFilterLabel()."</label>
						<select id='".$this->columnName."' class='form-control cardstatus' name='filter:cardstatus:".$this->columnName."'>
							<option value=''> --- Select Status --- </option>".
							$result
						."</select>
				</div>
				",
				"
				<input type='hidden' class='cardstatus' name='filter:cardstatus:".$this->columnName."' value='".$this->cardstatus."' >
				");
	}
	
	public function getFilterValue(){
		return "&nbsp;".$this->cardstatus;
	}
}