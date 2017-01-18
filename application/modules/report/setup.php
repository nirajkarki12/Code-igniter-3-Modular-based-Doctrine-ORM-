<?php
function report_permissions(){
	return array(	
			'report menu'		=>	'Access the permitted reports from mainmenu.',
			'view report'		=>	'View existing reports as a list.',
			// 'edit report'		=> 	'Modify report permission.',
			// 'delete report'		=> 	'Delete report.',
		);
}

$allReports = CI::$APP->db->get('tbl_reports')->result_array();
foreach ($allReports as $k=>$r) {
	if (!report_access($r['id'])) unset($allReports[$k]);
}
$summary = FALSE;

if (count($allReports) > 0) {
	
		$report = new \MainMenuItem();
		$report->setId('MM_REPORT_CUSTOM');
		$report->setName("Reports");
		$report->setIcon('book');
		$report->setOrder(10);
		$report->setPermissions(array('report menu'));
		$report->setRoute(current_url().'#');
		\MainMenu::register($report);
	
	$reportgroups 	= CI::$APP->db->get('tbl_report_groups')->result_array();
	$groups 		= array();
	
	foreach ($reportgroups as $rg) {
		
		$reports = CI::$APP->db->get_where('tbl_reports',  array('reportgroup_id' => $rg['id']))->result_array();
		foreach ($reports as $k=>$r) {
			if (!report_access($r['id'])) unset($reports[$k]);
		}
	
		if (count($reports) > 0) {
			
			$group = new \MainMenuItem();
			$group->setId('MM_REPORT_GROUP_' . $rg['id']);
			$group->setName($rg['name']);
			$group->setPermissions(array('report menu'));
			$group->setRoute(current_url().'#');
			$group->setParent($report);
			\MainMenu::register($group);

			foreach ($reports as $r) {
				
				$item = new \MainMenuItem();
				$item->setId('MM_REPORT_ITEM_'.$r['id']);
				$item->setName(ucwords($r['name']));
				$item->setPermissions(array('report menu'));
				$item->setParent($group);
				$item->setRoute(site_url('report/result/'.$r['slug']));
				\MainMenu::register($item);
			}
	
		}
	}
}