<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Pagination extends CI_Pagination{

	public function create_links()
	{
		$links = parent::create_links();
		$cur_page_item_start = ($this->cur_page == 1 )? $this->cur_page : ( ($this->cur_page - 1 ) * $this->per_page + 1 );
		$cur_page_item_end = ($cur_page_item_start + $this->per_page - 1) >= $this->total_rows ? $this->total_rows : ($cur_page_item_start + $this->per_page - 1);

		$cur_page_info = "<p class='pagination_info'>Showing " . $cur_page_item_start . " to ". $cur_page_item_end ." of ". $this->total_rows .' entries</p>';

		if( $this->total_rows > $this->per_page ){
			return $cur_page_info . $links;
		}elseif($this->total_rows!=0){
			return "<p class='pagination_info'>Showing 1 to ". $this->total_rows . " of ". $this->total_rows ." entries</p>";
		}

	}
}
	