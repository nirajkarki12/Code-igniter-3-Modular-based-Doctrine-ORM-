<?php sm_get_header();
if(isset($maincontent)) $this->load->theme($maincontent);
sm_get_footer(); 

if (\Options::get('site_maintenance', '0')=='1') exit;
