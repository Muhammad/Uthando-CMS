<?php

// no direct access
defined( 'PARENT_FILE' ) or die( 'Restricted access' );

if ($this->authorize()) {
	
	$menuBar = array(
		'cancel' => '/ushop/products/overview',
		'save' => null,
   		'seperator' => null,
   		'customers' => '/ushop/customers',
   		'postage' => '/ushop/postage',
   		'tax' => '/ushop/tax'
	);
		
	$this->content .= $this->makeToolbar($menuBar, 24);
	
	$menuBar = array();
	
	$ushop = new UShopAdmin();
	
	if ($this->registry->params['id']) {
		
		$rows = $this->getResult('author_id, forename, surname', $ushop->db_name.'authors', null, array('where' => 'author_id = '.$this->registry->params['id']));
			
		$form = new HTML_QuickForm('edit_author', 'post', $_SERVER['REQUEST_URI']);
			
		// Remove name attribute for xhtml strict compliance.
		$form->removeAttribute('name');
			
		$form->addElement('html', '<fieldset>');
		$form->addElement('header','edit_author','Edit Author');
		
		$form->addElement('text', 'forename', 'Forename:', array('size' => 30, 'maxlength' => 30, 'class' => 'inputbox'));
	
		$form->addElement('text', 'surname', 'Surname:', array('size' => 30, 'maxlength' => 30, 'class' => 'inputbox'));
			
		$form->addElement('html', '</fieldset>');
		
		$form->addRule('forename', 'Please enter a forename', 'required');
		$form->addRule('surname', 'Please enter a surname.', 'required');
			
		if ($form->validate()) {
			
			$form->freeze();
			$values = $form->process(array(&$this, 'formValues'), false);
			
			// format values.
			foreach ($values as $key => $value) {
				$values[$key] = ucwords($value);
			}
			
			$menuBar['back'] = '/ushop/products/overview';
			
			//check then enter the record.
			$res = $this->update($values, $ushop->db_name.'authors', array('where' => 'author_id='.$this->registry->params['id']));
			
			if ($res) {
				$params['TYPE'] = 'pass';
				$params['MESSAGE'] = '<h2>Author was successfully edited.</h2>';
				
			} else {
				$params['TYPE'] = 'error';
				$params['MESSAGE'] = '<h2>Author could not be edited due to an error.</h2>';
			}
				
			// done!
			
		} else {
				
			$form->setDefaults(array(
   				'forename' => $rows[0]->forename,
	   			'surname' => $rows[0]->surname,
			));
				
			$renderer = new UthandoForm(__SITE_PATH . '/templates/' . $this->registry->admin_config->get ('admin_template', 'SERVER'));
			
			$renderer->setFormTemplate('form');
			$renderer->setHeaderTemplate('header');
			$renderer->setElementTemplate('element');
		
			$form->accept($renderer);
		
			// output the form
			$this->content .= $renderer->toHtml();
		
		}
	
		if (isset($params)) {
			$params['CONTENT'] = $this->makeToolbar($menuBar, 24);
			$this->content .= $this->message($params);
		}
	}
	
} else {
	header("Location:" . $registry->config->get('web_url', 'SERVER'));
	exit();
}
?>