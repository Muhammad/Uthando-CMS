<?php

// no direct access
defined( 'PARENT_FILE' ) or die( 'Restricted access' );

if ($this->authorize()) {
	
	if (is_readable($this->file."/action/".$this->registry->action.".php") == false || $this->registry->action == "index") {
		$this->registry->Error('404 Page NOT Found', $this->registry->path);
		$this->AddParameter ('PAGE',  'Page Not Found');
	} else {

		$title = ucwords($this->registry->component) . " " . ucwords($this->registry->action);
		$this->registry->page_title = ucwords($this->registry->component);
		$this->setTitle($title . ' | ' . $this->registry->config->get('site_name', 'SERVER'));
		
		require_once('action/'.$this->registry->action.'.php');
		
		$this->AddParameter ('PAGE',  $title);

		if (is_array($this->registry->component_css)) {
			$this->registry->component_css = array_merge(array('/components/content/css/content.css'), $this->registry->component_css);
		} else {
			$this->registry->component_css = array('/components/content/css/content.css');
		}
	}

} else {
	header("Location:" . $registry->config->get('web_url', 'SERVER'));
	exit();
}
?>