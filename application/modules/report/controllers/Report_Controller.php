<?php 

use models\User;

use RQL\RQLExecuter;

use models\Common\Report;
use models\Common\ReportGroup;
use models\Group;


class Report_Controller extends Admin_Controller{
	
	private $columns;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		redirect('config#report-config');
	}
	
	public function editor(){
		try {

		$this->breadcrumb->append_crumb('Configuration', site_url('config'));
		$this->breadcrumb->append_crumb('Reports',site_url('config#report-config'));
		$this->breadcrumb->append_crumb('Add Report', current_url());
		
		$groupRepo = $this->doctrine->em->getRepository('models\Group');
		$reportRepo = $this->doctrine->em->getRepository('models\Common\Report');
		$reportGrpRepo = $this->doctrine->em->getRepository('models\Common\ReportGroup');
	
		if($this->input->post('gen-report')) {
			$this->form_validation->set_rules('sqlquery', 'Report Generator Query', 'required|trim|callback_isValidQuery');
			if ($this->form_validation->run($this)) 
			{
				$this->render(NULL, 'col');
			}
		}
	
		if ($this->input->post('save-report')){
	
			$this->form_validation->set_rules('sqlquery', 'Report Generator Query', 'required|trim|callback_isValidQuery');
			$this->form_validation->set_rules('name', 'Report Name', 'required');
			$this->form_validation->set_rules('description', 'Report Descriptions', 'required');
	
			if ($this->form_validation->run($this)){
	
				$report = new Report();
				
				if (($newGroup = $this->input->post('reportgroup')) != '') {
						
					$exist = $reportGrpRepo->findOneByName($newGroup);
					if (! $exist) {
						$r_group = new ReportGroup($newGroup);
						$this->doctrine->em->persist($r_group);
						$this->doctrine->em->flush();
						if ($r_group->id()) {
							$report->setGroup($r_group);
						}
					} else $report->setGroup($exist);
						
				} else if ( is_numeric($r_gID = $this->input->post('reportgroup_id')) ) {
					$report->setGroup($this->doctrine->em->find('models\Common\ReportGroup', $r_gID));
				}
	
				$usrgrp = is_array($this->input->post('usrgrp')) ? $this->input->post('usrgrp') : array( 0 => Group::SUPER_ADMIN);
				$query = $this->input->post('sqlquery');
	
				$report->setName(trim($this->input->post('name')));
				$report->setDescr($this->input->post('description'));
				$report->setSqlQuery(str_replace(';', '', $query));
				$report->setUserGroups($usrgrp);
	
				$this->doctrine->em->persist($report);
				$this->doctrine->em->flush();
	
				if ($report->id()) {
	
					$this->message->set("Report saved successfully.", 'success', TRUE, 'feedback');
					redirect('config#report-config');
	
				}
			}
		}
		// loading all tables and its columns
		$tables = $this->db->list_tables();
		$tb = '';
		foreach ($tables as $table) {
			$tb[$table] = $this->db->list_fields($table);
		}

		$this->templatedata['tables'] = & $tb;
		$this->templatedata['reportgroups'] = $reportRepo->getReportGroups();
		$this->templatedata['groups'] = $groupRepo->getGroupLists();
		$this->templatedata['maincontent'] = 'report/editor';
		$this->templatedata['pageTitle'] = 'Add a Report';
		$this->load->theme('master',$this->templatedata);

		} catch (Exception $e) {
			$this->message->set("Could not Process :{$e->getMessage()}", 'error', TRUE, 'feedback');
			redirect('report/editor');
		}
	}
	
	public function change($slug = NULL) {
		
		$groupRepo = $this->doctrine->em->getRepository('models\Group');
		
		if (!$slug) redirect();
		$slug = str_replace('_', '-', $slug);
		
		$reportRepo = $this->doctrine->em->getRepository('models\Common\Report');
		$reportGrpRepo = $this->doctrine->em->getRepository('models\Common\ReportGroup');
		$report = $reportRepo->findOneBy(array(
				'slug' => $slug,
		));
			
		if (!$report or !report_access($report->id())) redirect();

		$this->templatedata['editmode'] = FALSE;
		
		if($this->input->post('gen-report')) { 
			$this->form_validation->set_rules('sqlquery', 'Report Generator Query', 'required|trim|callback_isValidQuery[' .$report->id() .']');
			if ($this->form_validation->run($this)) $this->render(NULL, 'col');
			$this->templatedata['editmode'] = TRUE;

		}
		if ($this->input->post('save-report')){
			
			$this->form_validation->set_rules('sqlquery', 'Report Generator Query', 'required|trim|callback_isValidQuery[' .$report->id() .']');
			$this->form_validation->set_rules('name', 'Report Name', 'required');
			$this->form_validation->set_rules('description', 'Report Descriptions', 'required');
				
			if ($this->form_validation->run($this)){
				
				if (($newGroup = $this->input->post('reportgroup')) != '') {
					
					$exist = $reportGrpRepo->findOneByName($newGroup);
					if (! $exist) {
						$r_group = new ReportGroup($newGroup);
						$this->doctrine->em->persist($r_group);
						$this->doctrine->em->flush();
						if ($r_group->id()) {
							$report->setGroup($r_group);
						}
					} else $report->setGroup($exist);	
					
				} else if ( is_numeric($r_gID = $this->input->post('reportgroup_id')) ) {
					$report->setGroup($this->doctrine->em->find('models\Common\ReportGroup', $r_gID));
				}
				
				$usrgrp = is_array($this->input->post('usrgrp')) ? $this->input->post('usrgrp') : array( 0 => Group::SUPER_ADMIN);
				$query = $this->input->post('sqlquery');
					
				$report->setName(trim($this->input->post('name')));
				$report->setDescr($this->input->post('description'));
				$report->setSqlQuery(str_replace(';', '', $query));
				$report->setUserGroups($usrgrp);
					
				$this->doctrine->em->persist($report);
				$this->doctrine->em->flush();
					
				if ($report->id()) {
						
					$this->message->set("Report saved successfully.", 'success', TRUE, 'feedback');
					redirect('config#report-config');
						
				}
			}	
		}
		
		$this->breadcrumb->append_crumb('Configuration', site_url('config'));
		$this->breadcrumb->append_crumb('Reports', site_url('config#report-config'));
		$this->breadcrumb->append_crumb('Change Report Query', current_url());
		
		// loading all tables and its columns
		$tables = $this->db->list_tables();
		$tb = '';
		foreach ($tables as $table) {
			$tb[$table] = $this->db->list_fields($table);
		}

		$this->templatedata['tables'] = & $tb;
		$this->templatedata['report'] = & $report;
		$this->templatedata['reportgroups'] = $reportRepo->getReportGroups();
		$this->templatedata['groups'] = $groupRepo->getGroupLists();
		$this->templatedata['maincontent'] = 'report/modify';
		$this->templatedata['pageTitle'] = 'Change Report Query';
		$this->load->theme('master',$this->templatedata);
		
	} 
	
	public function result($slug, $offset = 0){
		if (!$slug) redirect();
		
		$slug = str_replace('_', '-', $slug);
		$reportRepo = $this->doctrine->em->getRepository('models\Common\Report');
		$report = $reportRepo->findOneBy(array(
				'slug' => $slug,
		));

		if (!$report or !report_access($report->id())) redirect();

		$this->render($report->getSqlQuery(), 'col');
		
		$this->templatedata['report'] = &$report;
		
		$this->breadcrumb->append_crumb('Reports', site_url());
		$this->breadcrumb->append_crumb($report->getGroup() ? $report->getGroup()->getName() : '', site_url());
		$this->breadcrumb->append_crumb($report->getName(), current_url());

		$this->templatedata['pageTitle'] = $report->getName();
		$this->templatedata['maincontent'] = 'report/exec-rql';
		$this->load->theme('master',$this->templatedata);}
	
	public function delete($slug){

		if (!user_access(array(
								'delete report',
								))
						) redirect();
		
		$slug = str_replace('_', '-', $slug);

		$reportRepo = $this->doctrine->em->getRepository('models\Common\Report');
		$report = $reportRepo->findOneBy(array(
										'slug' => $slug,
									));
		 
		if (!$report or !report_access($report->id())) redirect();
		
		$this->doctrine->em->remove($report);
		$this->doctrine->em->flush();
		
		$this->message->set("Report Deleted successfully.", 'success', TRUE, 'feedback');
		redirect('config#report-config');
		
	}
	
	public function dumpxls($slug){
		
		$slug = str_replace('_', '-', $slug);

		$reportRepo = $this->doctrine->em->getRepository('models\Common\Report');
		$report = $reportRepo->findOneBy(array(
				'slug' => $slug,
		));
			
		if (!$report or !report_access($report->id())) redirect();
	
		$name = str_replace(array('"', "'", ',', ' '), '-', $report->getName().date(' Y m d H i'));
	
		$this->output->set_header('Content-type: application/octet-stream');
		$this->output->set_header("Content-Disposition: attachment; filename=$name.xls");
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header("Expires: 0");
	
		$this->render($report->getSqlQuery(), 'col', TRUE);
		$this->templatedata['report'] = &$report;
	
		$this->load->view('admin/dumpxls', $this->templatedata);
	}
	
	private function generateTable($table,$head){
	
		$txns = $table['transactions'];
		$first = $txns[0];
		$columns = array_keys($first);
		$this->columns = $columns;
		$html = '';
	
		$html .="
					<div class='box-body table-responsive'>
				";
		$html .= '<table id="paginateTable" class="table table-bordered table-striped table-hover">';
		$html .= "<thead><tr>";
		$i = 0;
		foreach($columns as $c){
			$html .= "<th>&nbsp;{$c}</th>";
		}
		$html .= "</tr></thead>";
		
		foreach($txns as $tx){
			$class = $i%2==0 ? 'even' : 'odd';
			$html .= "<tr class='{$class}'>";
				
			foreach($columns as $c){
				$html .= "<td>&nbsp;{$tx[$c]}</td>";
			}
				
			$html .= "</tr>";
			$i++;
		}
	
		if(isset($table['aggregates'])){
			
			$agg = $table['aggregates'];
			$html .= "<tr class='aggregate'>";
			
			foreach($columns as $c){
				if(isset($agg[$c])){
					$html .= "<td title='Sub Total'>". number_format($agg[$c]['SUM'], 2, '.', ','). "</td>";
				}else{
					$html .= "<td>&nbsp;</td>";
				}
			}
			
			$html .= "</tr>";
		}
	
		$html .= "</table>";
	
		return $html;
	}
	
	public function render($rql = NULL, $display = '', $xls = FALSE) {
		
		if (! $rql)
			if ($this->input->post('sqlquery')) $rql = $this->input->post('sqlquery');
		if (!$rql) redirect();
			
		$exec = new RQLExecuter($rql, $this->db);
		$result = $exec->getResult();
			
		$output = '';
		$filterhtml = '';
		$hasResult = TRUE;
		$filters = $exec->getResultFilters();
		$filterHTML = '';
		$filterValues = '';
		if(count($filters) > 0) {
			
			$checks = array();
			
			foreach($filters as $k => $f){ 
				
				$check = $f->name.' '.$f->getFilterLabel();
				
				if ($f->name == 'or' or array_search($check, $checks) !== FALSE) {
					unset($filters[$k]);
					continue;
				}
				
				$checks[] = $check;
			}
			
			$filterHTML .= '<div class="box-body">';
			
			$filterHTML .= '<div class="row">';
			
			foreach($filters as $f){
				$HTML =  $f->getFilterElement();
				$filterHTML .=$HTML[0];
				$filterValues .= $HTML[1];
			}

			$filterHTML .= '</div>';

			// if (!$xls)
				$filterHTML	.=	'
			<div class="row">
				<div class="col-xs-12 form-group">
					<input type="submit" class="btn btn-primary" value="Filter" id="submit-filter" name="a_filter">
					<input id="clear" type="submit" class="btn btn-danger" value="Clear">
				</div>
			</div>
						';
		}

		if($result['rowCount'] > 0){
			if($result['tablized']){
				foreach($result['result'] as $t => $d){
						$output .= $this->generateTable($d,$t);
				}
			}else{
				$output .= $this->generateTable($result,'Total ');
			}
			
			// $output .= '
			// <div style="background:#e5e5e5;padding:5px;">
			// 	<strong>Total Records : </strong>'.$result['rowCount'].'
			// </div>';

			$output .= $this->generateGrandTotal($result, $display);
			
		}else{
			$output .= '
			<div style="background:#e5e5e5;padding:5px">
				No matching results found. You can change the filters above (if any).
			</div>
			';
			$hasResult = FALSE;
		}
		$this->templatedata['query_result'] = $output;
		$this->templatedata['filterValues'] = $filterValues;
		// $this->templatedata['pagination'] = $result['links'];
		$this->templatedata['filter'] = $filterHTML;
		$this->templatedata['xls'] = $xls;
		$this->templatedata['hasResult'] = $hasResult;

	}
	
	public function isValidQuery($query, $rid=NULL){
	
		$repo = $this->doctrine->em->getRepository('models\Common\Report');
	
		$report = $repo->findOneBy(array('sqlquery'=>str_replace(';', '', $query)));
	
		if (isset($rid)) {
				
			if ($report and $report->id() != $rid) {
				$this->form_validation->set_message('isValidQuery', 'This Report Generator Query already exists. <br/>');
				return FALSE;
			}
		} else {
			if ($report) {
				$this->form_validation->set_message('isValidQuery', 'This Report Generator Query already exists. <br/>');
				return FALSE;
			}
		}
	
		if (!preg_match("/^select (.*)/i", trim($query)) > 0) {
			$this->form_validation->set_message('isValidQuery', 'Report Generator Query must contain valid SELECT statements only. <br/>');
			return FALSE;
		}

		if (preg_match("/(limit)/", trim($query))) {
			$this->form_validation->set_message('isValidQuery', 'SQL Server Does not Support LIMIT statements. <br/>');
			return FALSE;
		}

		if (preg_match('/(")/', trim($query))) {
			$this->form_validation->set_message('isValidQuery', 'SQL Server Does not Support Double inverted commas(") statements. <br/>');
			return FALSE;
		}
			
		$prep_query = str_replace(array("\n", "\r\n", "\r"), ' ', $query);
		$prep_query = preg_replace('!\s+!', ' ', $prep_query);
	
		// if ((strtolower(substr($prep_query, 0, 8))=='select *') or strstr($prep_query,'.*')!==FALSE) {
		// 	$this->form_validation->set_message('isValidQuery', 'Report Generator Query may not contain WILD selector [ * ]. <br/>');
		// 	return FALSE;
		// }
	
		$delimeter_position = strpos(trim($prep_query), ';');
	
		if ($delimeter_position===FALSE) {
		} else {
				
			if ($delimeter_position+1 != strlen(trim($prep_query))) {
	
				$this->form_validation->set_message('isValidQuery', 'Report Generator Query may not contain MULTIPLE statements delimited by semicolon ( ; ). <br/>');
				return FALSE;
			}
		}
		// excluding custom date filter
		$exception = preg_replace('/(filter\:date\:.*)/', "='".date('Y-m-d')."'", $query);
		// excluding custom date between statement 
		$exception = preg_replace('/(between)/i', '', $exception);
		// excluding custom Account Number filter
		$exception = preg_replace('/(filter\:account\:.*)/', "'1001002104601120'", $exception);
		// excluding custom Branch Code filter
		$exception = preg_replace('/(filter\:branchcode\:.*)/', "'001'", $exception);
		// excluding custom Card filter
		$exception = preg_replace('/(filter\:card\:.*)/', "'1001002XXX104601'", $exception);
		// excluding custom Card Status filter
		$exception = preg_replace('/(filter\:cardstatus\:.*)/', "1", $exception);
		// excluding custom Remarks filter
		$exception = preg_replace('/(filter\:remarks\:.*)/', "'NEW'", $exception);
		// excluding custom Branch filter
		$exception = preg_replace('/(filter\:branch\:.*)/', "1", $exception);
		// excluding custom Repin Status filter
		$exception = preg_replace('/(filter\:repinstatus\:.*)/', "1", $exception);
		// excluding custom Freetext filter
		$exception = preg_replace('/(filter\:freetext\:.*)/', "'test'", $exception);

		if(!$this->db->simple_query($exception)){
			if(DB_ACTIVE == 'mssql')
			{
				$error = is_array(sqlsrv_errors()) ? sqlsrv_errors()[0]['message'] : '';
			}elseif(DB_ACTIVE == 'mysql')
			{
				$error = is_array(mysql_error()) ? mysql_error()[0]['message'] : '';
			}
			$this->form_validation->set_message('isValidQuery', "Invalid SQL server statements - {$error} <br/>");
			return FALSE;
		}
		return TRUE;
	}

	private function generateGrandTotal($result, $display=''){
		
		$html = '';
	
		if(isset($result['aggregates'])){
			
			$agg = $result['aggregates'];
			$html .="<h3>Grand Total</h3>";
			$html .= "<table border='0' width='100%' cellpadding='0' cellspacing='0' class='grand-total'>";
			
			if ($display == 'col') {
				
				$html .= "<tr>";
				foreach ($agg as $col => $val) $html .= "<th>{$col}</th>";
				$html .= "</tr>";
				
				$html .= "<tr class='aggregate'>";
				foreach ($agg as $col => $val) $html .= "<td align='center' title='Grand Total'>". number_format($val['SUM'], 2, '.', ',') ."</td>";
				$html .= "</tr>";
			} 
			
			if ($display == 'row' ) {
				
				foreach ($agg as $col => $val) $html .= "<tr class='aggregate'><th>{$col}</th><td align='center' title='Grand Total'>". number_format($val['SUM'], 2, '.', ',') ."</td></tr>";
				
			}
			
			if ($display == '')	{
				
			  $html .= "<tr class='aggregate'>";
				foreach($this->columns as $c){
					if(isset($agg[$c])){
						$html .= "<td title='Grand Total'>". number_format($agg[$c]['SUM'], 2, '.', ','). "</td>";
					}else{
						$html .= "<td>&nbsp;</td>";
					}
				}
				$html .= "</tr>";
			
			}	
			
			$html .= "</table>";
		}
		
		return $html;
	}

}

