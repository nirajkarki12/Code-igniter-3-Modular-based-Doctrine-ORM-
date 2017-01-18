<?php

class OnlineUsers extends Widget
{
	function run(){
// 		$tolerance = 60;
// 		$required_last_activity = time() - $tolerance; 
		
// 		$this->db->select('user_data');
// 		$this->db->from($this->config->item('sess_table_name'));
// 		$this->db->where("user_data <> ''");
// 		$users = $this->db->get()->result_array();

		
		
		$data = array();
		
// 		if(count($users)){
// 			foreach($users as $user)
// 			{
// 				$user_data = unserialize($user['user_data']);
				
// 				$u = $this->doctrine->em->find('models\User',$user_data['user_id']);
// // 				echo "<p><label>[online] ".$u->getUsername()."</label></p>";
// 				$data['online_users'][] = $u->getUsername();
// 			}
// 		}
		
		$this->render('onlineUsers', $data);
		
	}
}