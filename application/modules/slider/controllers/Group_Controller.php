<?php

use models\Group;

class Group_Controller extends Admin_Controller{
	private $adminGroupId = null;

	private $adminGroup = null;

	public function __construct()
	{
		parent::__construct();
				
		$this->breadcrumb->append_crumb('Users', site_url('user'));
		$this->breadcrumb->append_crumb('Groups', site_url('user/group'));
	}
	
	public function index()
	{
		if (!user_access(array('view user groups'))) redirect();
		$this->session->unset_userdata('group');
		
		$perpage = \Options::get('offset');
		$filters = array();
		
		$param = '';
		$geturi = '';
		$post = NULL;
		$offset = $this->input->get('per_page');
 		
 		if($this->input->get('a_filter')){
 			$post = $this->input->get();
 			$filters = $post;
 			
 			foreach($post as $k=>$v){
 				if($k !== 'per_page') $param .=  $k.'='.$v.'&'; 
 			}
 			$param = substr($param,0,-1);
 			$geturi = '?' . http_build_query($post, '', '&');
 		}

 		$groups = $this->paginate_data($this->doctrine->em->getRepository('models\Group')->getGroupListPaginate($filters), $offset, $perpage );
		$this->paginate_view($groups, base_url().'user/group/index?'.$param, $perpage, 4);
	
		if($this->input->get('do')=='xls')
 		{
 			$this->load->helper('card/card');
 			$file_name = 'Group(s)';
 			exportxls($file_name);
			$this->load->theme('user/report/group', array('groups'=> $groups,'offset'=>$offset));
			return;
 		}

		$this->templatedata['groups'] = $groups;
		$this->templatedata['adminGroupId'] = $this->adminGroupId;
		$this->templatedata['offset'] = $offset;
		$this->templatedata['filters']=$filters;
		$this->templatedata['per_page'] = $perpage;
		$this->templatedata['maincontent'] = 'user/group/list';
		$this->templatedata['pageTitle'] = 'Groups';
		$this->load->theme('master',$this->templatedata);
	}
	
	public function add()
	{
		if (!user_access(array('add user group'))) redirect();

		if($this->input->post()){
			$this->form_validation->set_rules('name','Group Name','trim|required|required|unique_groupName');
			$this->form_validation->set_rules('description','Group Description','trim|required');
			
			if($this->form_validation->run($this)){
				// $group = new Group();
				// $group->setName($this->input->post('name'));
				// $group->setDescription($this->input->post('description'));
				// $group->activate();
						
				// $this->doctrine->em->persist($group);
				// $this->doctrine->em->flush();
				// New group has been added successfully


				$this->session->set_userdata(array('group'=>$this->input->post()));
				
				// if($group->getId()){
					// $groupName = $this->input->post('name');
					// $groupDesc = $this->input->post('description');
					$this->message->set("Please add some Permisssions to '{$this->input->post('name')}' Group", 'success',TRUE,'feedback');
					redirect('user/group/assign-permissions');
				// }else{
				// 	$this->message->set("Could not add group. Please try again.", 'error',TRUE,'feedback');
				// 	redirect('user/group');
				// }
			}
		}

		$this->breadcrumb->append_crumb('Add Group', current_url());
		
		$this->templatedata['maincontent'] = 'user/group/add';
		$this->templatedata['pageTitle'] = 'Add Group';
		$this->load->theme('master',$this->templatedata);
	}
	
	public function edit($group_id = 0){
		if (!user_access(array('modify user group'))) redirect();
		
		try {
			$group = $this->doctrine->em->find('models\Group', $group_id);
			if(!$group) throw new Exception("Group not found.", 1);

			// restrict if group is admin group of company
			if($group->getId() == $this->adminGroupId) throw new Exception("Illegal operation.", 1);

			// process only if group is active
			if($group->isDeleted() || $group->isBlocked()) throw new Exception("Group '{$group->getName()}' is currently disabled.", 1);			
			
			if($this->input->post()){
				$this->form_validation->set_rules('name','Group Name','trim|required|unique_groupName_edit['.$group->getId().']');
				$this->form_validation->set_rules('description','Group Description','trim|required');
				$this->form_validation->set_rules('status','Group Status','trim|required');
					
				if($this->form_validation->run($this)){
					$group->setName($this->input->post('name'));
					$group->setDescription($this->input->post('description'));
					$group->setStatus($this->input->post('status'));
			
					$this->doctrine->em->flush();
			
					$this->message->set("Group '{$group->getName()}' has been updated successfully.", 'success',TRUE,'feedback');
					redirect('user/group');		
				}
			}
			
			$this->breadcrumb->append_crumb('Edit Group', current_url());
			
			$this->templatedata['group'] = $group;
			$this->templatedata['maincontent'] = 'user/group/edit';
			$this->templatedata['pageTitle'] = 'Edit Group';
			$this->load->theme('master',$this->templatedata);
				
		} catch (Exception $e) {
			$this->message->set("Unable to edit group: {$e->getMessage()}", 'error',TRUE,'feedback');
			redirect('user/group');
		}
	}

	public function block($group_id = 0){
		if (!user_access(array('modify user group'))) redirect();
		
		try {
			$group = $this->doctrine->em->find('models\Group', $group_id);
			if(!$group) throw new Exception("Group not found.", 1);

			// restrict if group is admin group of company
			if($group->getId() == $this->adminGroupId) throw new Exception("Illegal operation.", 1);

			// process only if group is active
			if(!$group->isActive()) throw new Exception("Group '{$group->getName()}' is currently disabled.", 1);

			$group->deactivate();
			$this->doctrine->em->flush();

			$this->message->set("Group '{$group->getName()}' has been blocked. User assigned to this group will not be able to login.", 'success', TRUE, 'feedback');
			redirect('user/group');

		} catch (Exception $e) {
			$this->message->set("Unable to block group: {$e->getMessage()}", 'error',TRUE,'feedback');
			redirect('user/group');
		}
	}

	public function unblock($group_id = 0){
		if (!user_access(array('modify user group'))) redirect();
		
		try {
			$group = $this->doctrine->em->find('models\Group', $group_id);
			if(!$group) throw new Exception("Group not found.", 1);

			// restrict if group is admin group of company
			if($group->getId() == $this->adminGroupId) throw new Exception("Illegal operation.", 1);
			
			// process only if group is blocked
			if(!$group->isBlocked()) throw new Exception("Group '{$group->getName()}' is not blocked yet.", 1);

			$group->activate();
			$this->doctrine->em->flush();

			$this->message->set("Group '{$group->getName()}' has been activated.", 'success', TRUE, 'feedback');
			redirect('user/group');

		} catch (Exception $e) {
			$this->message->set("Unable to unblock group: {$e->getMessage()}", 'error',TRUE,'feedback');
			redirect('user/group');
		}
	}

	public function delete($group_id = 0){
		if (!user_access(array('modify user group'))) redirect();
		
		try {
			$group = $this->doctrine->em->find('models\Group', $group_id);
			if(!$group) throw new Exception("Group not found.", 1);

			// restrict if group is admin group of company
			if($group->getId() == $this->adminGroupId) throw new Exception("Illegal operation.", 1);
			
			// process only if group is active
			if(!$group->isActive()) throw new Exception("Group '{$group->getName()}' is currently disabled.", 1);

			$group->delete();
			$this->doctrine->em->flush();

			$this->message->set("Group '{$group->getName()}' has been deleted.", 'success', TRUE, 'feedback');
			redirect('user/group');

		} catch (Exception $e) {
			$this->message->set("Unable to unblock group: {$e->getMessage()}", 'error',TRUE,'feedback');
			redirect('user/group');
		}
	}
		
	public function permissions($group_id = 0){
		if (!user_access(array('moderate permission'))) redirect();

		try {
			$group = $this->doctrine->em->find('models\Group', $group_id);
			if(!$group) throw new Exception("Group not found.", 1);

			// process only if group is active
			if(!$group->isActive()) throw new Exception("Group '{$group->getName()}' is currently disabled.", 1);
			
			if($this->input->post()){

				$group->resetPermissions();
				$assignedPermissions = $this->input->post('permission');

				if ($assignedPermissions) {					
					foreach($assignedPermissions as $k => $v){
						$perm = $this->doctrine->em->find('models\Permissions', $k);
						$group->addPermission($perm);
					}
					$this->doctrine->em->persist($group);
					$this->doctrine->em->flush();
					
					$this->message->set("Group permissions has been set successfully.", 'success', TRUE, 'feedback');
					redirect('user/group');
				}				
				else {
					$this->message->set("Please check at least one permission for this group!", 'error', TRUE, 'feedback');
					redirect('user/group/permissions/'.$groupId);
				}
			}

			$permission_index = array();
			$group_permissions = $group->getPermissions();
			
			foreach($group_permissions as $p)
				$permission_index[] = $p->getId();	
		
			$gRepo = $this->doctrine->em->getRepository('models\Group');
			$db_permissions = $gRepo->getPermissions();
			$db_perms = array();
			foreach ($db_permissions as $d){
				$db_perms[$d['name']] = $d['perm_id'];
			}
			
			$this->breadcrumb->append_crumb('Permissions', current_url());

			$this->templatedata['db_permissions'] = $db_perms;
			$this->templatedata['all_permissions'] = ModuleManager::permissionArray();
			$this->templatedata['group'] = $group;
			$this->templatedata['group_permissions'] = $permission_index;
			$this->templatedata['maincontent'] = 'user/group/permissions';
			$this->templatedata['pageTitle'] = 'Permissions';
			$this->load->theme('master',$this->templatedata);

		} catch (Exception $e) {
			$this->message->set("Could not assign permissions: {$e->getMessage()}", 'error', TRUE, 'feedback');
			redirect('user/group');
		}
	}

	public function assign_permissions(){
		if (!user_access(array('moderate permission'))) redirect();

		$sessionGroup = $this->session->userdata('group');

		try {
			if(!$this->session->has_userdata('group')) throw new Exception("Group not found.", 1);

			if($this->input->post()){

				$group = new Group();
				$group->setName($sessionGroup['name']);
				$group->setDescription($sessionGroup['description']);
				$group->activate();
				
				$this->doctrine->em->persist($group);
				$this->doctrine->em->flush();

				$group->resetPermissions();
				$assignedPermissions = $this->input->post('permission');

				if ($assignedPermissions) {					
					foreach($assignedPermissions as $k => $v){
						$perm = $this->doctrine->em->find('models\Permissions', $k);
						$group->addPermission($perm);
					}
					$this->doctrine->em->persist($group);
					$this->doctrine->em->flush();
					// Group permissions has been set successfully.
					$this->message->set("New group has been added successfully ", 'success', TRUE, 'feedback');
					redirect('user/group');
				}				
				else {
					$this->message->set("Please check at least one permission for this group!", 'error', TRUE, 'feedback');
					redirect('user/group/assign-permissions/');
				}
			}

			$permission_index = array();
			// $group_permissions = $group->getPermissions();
			
			// foreach($group_permissions as $p)
			// 	$permission_index[] = $p->getId();	
		
			$gRepo = $this->doctrine->em->getRepository('models\Group');
			$db_permissions = $gRepo->getPermissions();
			$db_perms = array();
			foreach ($db_permissions as $d){
				$db_perms[$d['name']] = $d['perm_id'];
			}
			
			$this->breadcrumb->append_crumb('Assign Permissions', current_url());

			$this->templatedata['db_permissions'] = $db_perms;
			$this->templatedata['all_permissions'] = ModuleManager::permissionArray();
			$this->templatedata['group_permissions'] = $permission_index;
			$this->templatedata['maincontent'] = 'user/group/assign_permissions';
			$this->templatedata['pageTitle'] = 'Permissions';
			$this->load->theme('master',$this->templatedata);

		} catch (Exception $e) {
			$this->message->set("Could not assign permissions: {$e->getMessage()}", 'error', TRUE, 'feedback');
			redirect('user/group');
		}
	}


}