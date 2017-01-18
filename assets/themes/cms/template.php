<?php

function sm_get_header()
{
	CI::$APP->load->theme('common/header');
}

function sm_get_footer()
{
	CI::$APP->load->theme('common/footer');
}

function sm_get_header_assets()
{
	CI::$APP->load->theme('common/header_assets');
}

function sm_get_footer_assets()
{
	CI::$APP->load->theme('common/footer_assets');
}

function sm_get_menu()
{
	CI::$APP->load->theme('common/brand_bar');
	CI::$APP->load->theme('common/menu_bar');
}