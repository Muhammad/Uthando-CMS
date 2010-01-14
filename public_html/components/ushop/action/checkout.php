<?php
// no direct access
defined( 'SHOP_PARENT_FILE' ) or die( 'Restricted access' );

$title .= 'Checkout';

if ($this->ushop->GLOBAL['offline'] || $this->ushop->GLOBAL['catelogue_mode']) {
	$this->addContent('<h3>Shopping is unavialible</h3><p><a href="/ushop/view/shopfront">Click here to continue</a></p>');
} else {
	if (UthandoUser::authorize()):

		switch ($this->registry->params['stage']):
			case 3:
				require_once('ushop/checkout/stage3.php');
				break;
			case 2:
				require_once('ushop/checkout/stage2.php');
				break;
			case 1:
			default:
				$cart = $this->ushop->retrieveCart();
				if ($cart->cart == null) goto();
				require_once('ushop/checkout/stage1.php');
				break;
		endswitch;
	else:

		$_SESSION['http_referer'] = '/ushop/view/checkout';

		switch ($this->registry->params['new_customer']):
			case 1:
				$this->addContent('<h3>New Customers</h3><br />');
				require_once('user/action/register.php');
				break;

			default:
				$this->addContent('<h3>Login to Checkout</h3><br />');
				
				require_once('user/action/login.php');
				
				$this->addContent('<h3>New Customers</h3><br />');
				$this->addContent('<div id="products"><a class="button" href="'.$this->registry->config->get('ssl_url', 'SERVER').'/ushop/view/checkout/new_customer-1">Click here to create an account</a></div>');
				break;
		endswitch;
	endif;
	
}
?>