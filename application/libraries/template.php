<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Version       : 1.0
 * Filename      : template.php
 * Purpose       : This class is used to render of template.
 */    
class Template {
	
	public $ciInstance;
	public $title;
	public $content;
	public $jsLink;
	public $cssLink;
	public $metaData='';
	public $staticData = '';
	public $timeZone = false;
	/*Methodname:  __construct
        *Purpose:Perform common action for class at load 
       */
	public function __construct() {
		$this->ciInstance = & get_instance();
	}
	
	/*Methodname:  addJSLink
        *Purpose: This function add extenal js links to header section
        */
	
	public function addJSLink($jsLinks) {
		if(is_array($jsLinks)) {
			foreach($jsLinks as $link)
				$this->jsLink .='<script type="text/javascript" src="'.$link.'"></script>';
		} else
			$this->jsLink .= '<script type="text/javascript" src="'.$jsLinks.'"></script>';
	}
	
	
	/*Methodname:  addMetaData
        *Purpose: This function add meta data
       */
	public function addMetaData($metaData){
		if(is_array($metaData)) {
			$metaContent='';
			foreach($metaData as $metaLine){
				$metaContent .='<meta ';
				foreach($metaLine as $attr=>$value) {
					$metaContent .=$attr.'="'.$value.'" ';
				}
				$metaContent .='/>';
			}
			$this->metaData = $metaContent;
		} else {
			if(!empty($metaData))
				$this->metaData = $metaData;
		}
	}
	
	/*Methodname:  addCSSLink
        *Purpose: This function add extenal css links to header section
        */
	public function addCSSLink($csslinks) {
		$content = '';
		if(is_array($csslinks)) {
			foreach($csslinks as $link)
				$this->cssLink .='<link type="text/css" href="'.$link.'" rel="stylesheet" />'; 
		} else
			$this->cssLink .= '<link type="text/css" href="'.$csslinks.'" rel="stylesheet" />';
	}
	
	/*Methodname:  renderTemplate
        *Purpose: This function is used for render template
        */
	public function renderTemplate() {
        
		$CI = $this->ciInstance;
		
		//title of the page
		if (isset($this->title) && !empty($this->title))
			$header['title'] = $this->title;
		$header['jsLink'] = $this->jsLink;
		$header['cssLink'] = $this->cssLink;
		$header['metaData'] = $this->metaData;
		$header['staticData'] = $this->staticData;
		$template['header'] = $CI->load->view('template/header_view', $header, TRUE);
		
		//get site menu
		$menu['menuContent'] = $CI->load->view('template/menu_view', '', TRUE);

		//load header bar
		$template['headerBar'] = $CI->load->view('template/header_bar_view', $menu, TRUE);
			
		//fill content into layout
		if (isset($this->content) && !empty($this->content))
			$contentarea['contentarea'] = $this->content;
		$template['content'] = $CI->load->view('template/content_view', $contentarea, TRUE);
        
		//load footer
		$data['timeZone'] = $this->timeZone;
		//$data = '';
		$template['footerBar'] = $CI->load->view('template/footer_bar_view', $data, TRUE);
		$template['footer'] = $CI->load->view('template/footer_view', '', TRUE);
		
		$CI->load->view('layout_view', $template);
	}
	
	/*Methodname:  renderAdminLoginTemplate
        *Purpose: This function is used to render admin login template
        */
	public function renderAdminLoginTemplate() {
        $CI = $this->ciInstance;
		//title of the page
		if (isset($this->title) && !empty($this->title))
			$header['title'] = $this->title;
			$header['jsLink'] = $this->jsLink;
		    $header['cssLink'] = $this->cssLink;
	        $template['header'] = $CI->load->view(ADMIN.'template/header_view', $header, TRUE);
			$template['menuBar'] = '';
			$template['headerBar'] = '';
		//fill content into layout
		if (isset($this->content) && !empty($this->content))
			$contentarea['contentarea'] = $this->content;
					
		$template['content'] = $CI->load->view(ADMIN.'template/content_view', $contentarea, TRUE);
		$template['footerBar'] = '';
		$template['footer'] = $CI->load->view(ADMIN.'template/footer_view', '', TRUE);
		$CI->load->view(ADMIN.'layout_view', $template);
	}
	
	/*Methodname:  renderAdminTemplate
        *Purpose: This function is used for admin render template
        */
	public function renderAdminTemplate() {
        
		$CI = $this->ciInstance;
		
		//title of the page
		if (isset($this->title) && !empty($this->title))
			$header['title'] = $this->title;
			
		$header['jsLink'] = $this->jsLink;
		$header['cssLink'] = $this->cssLink;
		
		$template['header'] = $CI->load->view(ADMIN.'/template/header_view', $header, TRUE);
		
		//get site menu
		$template['menuBar'] = $CI->load->view(ADMIN.'/template/menu_view', '', TRUE);
		
		//load header bar
		$template['headerBar'] = $CI->load->view(ADMIN.'/template/header_bar_view', '', TRUE);
		
		//fill content into layout
		if (isset($this->content) && !empty($this->content))
			$contentarea['contentarea'] = $this->content;
					
		$template['content'] = $CI->load->view(ADMIN.'/template/content_view', $contentarea, TRUE);
        
		//load footer
		$template['footerBar'] = $CI->load->view(ADMIN.'/template/footer_bar_view', '', TRUE);
		
		$template['footer'] = $CI->load->view(ADMIN.'/template/footer_view', '', TRUE);
		
		$CI->load->view(ADMIN.'/layout_view', $template);
	}
	
	/*Methodname:  renderSocialshareTemplate
        *Purpose: This function is used for rendering Share template
        */
	public function renderSocialshareTemplate() {
        
		$CI = $this->ciInstance;
		
		//title of the page
		if (isset($this->title) && !empty($this->title))
			$header['title'] = $this->title;
			
		$header['jsLink'] = $this->jsLink;
		$header['cssLink'] = $this->cssLink;
		$header['metaData'] = $this->metaData;
		$header['staticData'] = $this->staticData;
		$template['header'] = $CI->load->view('/template/header_view', $header, TRUE);
		
		//get site menu
		$template['menuBar'] = '';
		
		//load header bar
		$template['headerBar'] = '';
				
		//fill content into layout
		if (isset($this->content) && !empty($this->content))
			$contentarea['contentarea'] = $this->content;
					
		$template['content'] = $CI->load->view('/template/content_view', $contentarea, TRUE);
		
		//load footer
		$template['footerBar'] = '';
		        
		$template['footer'] = $CI->load->view('/template/footer_view', '', TRUE);
		
		$CI->load->view('/layout_view', $template);
	}

}


/* End of file template.php */
/* Location: ./system/libraries/template.php */