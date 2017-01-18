<?php
namespace RQL\Filters;
use RQL\Lexer;

use RQL\RQLFilter;

class DateFilter extends RQLFilter
{
	
	private $columnName;
	
	private $date = NULL;
	
	public function parse(\RQL\Parser $parser){
		
		$_lexer = $parser->getLexer();
		
		
		$parser->match(Lexer::T_IDENTIFIER);
		$this->columnName = $_lexer->token['value'];
	}
	
	public function getSql(){
		
		$default = (stripos( $this->columnName, 'from') !== FALSE) ? date('Y-m') . '-01' : date('Y-m-d');
		
		if (isset($_REQUEST['filter:date:'.$this->columnName])) {
			$requestDate = $_REQUEST['filter:date:'.$this->columnName];
			if (! isValidDate($requestDate)) $requestDate = NULL;
		}
		
		$date =  isset($requestDate) ? $requestDate : $default;
		$date .= stripos( $this->columnName, 'to') !== FALSE ? ' 23:59:59' : ' 00:00:00';
		
		$this->date = substr($date, 0, 10);
		
		return "'".$date."'";
	}
	
	public function getFilterLabel(){
		return trim(preg_replace(array('/([a-z]+)([A-Z]+)/', '/([A-Z]+)([A-Z])/'), array('$1 $2', '$1 $2'), $this->columnName));
	}
	
	public function getFilterElement(){
		return array('
				<div class="col-xs-12 col-md-4 form-group">
					<label for="'.$this->columnName.'">'.
						$this->getFilterLabel().'</label>
						<input type="text" id="'.$this->columnName.'" class="form-control datepicker" name="filter:date:'.$this->columnName.'" value='.$this->date.'  title="yyyy-mm-dd format" readonly>
				</div>
				',
				'
				<input type="hidden" class="datepicker" name="filter:date:'.$this->columnName.'" value="'.$this->date.'" >
				');
	}
	
	public function getFilterValue(){
		return "&nbsp;".$this->date;
	}
}