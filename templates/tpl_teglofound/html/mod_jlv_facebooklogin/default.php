<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');
?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-inline">
	<?php if ($params->get('pretext')) : ?>
		<div class="pretext">
		<p><?php echo $params->get('pretext'); ?></p>
		</div>
	<?php endif; ?>
	<div class="userdata">
		<div id="form-login-username" class="control-group">
			<div class="controls">
				<?php if (!$params->get('usetext')) : ?>
					<div class="input-prepend input-append">
						<span class="add-on">
							<span class="icon-user tip" title="<?php echo JText::_('MOD_JLV_FACEBOOKLOGIN_VALUE_USERNAME') ?>"></span>
							<label for="modlgn-username" class="element-invisible"><?php echo JText::_('MOD_JLV_FACEBOOKLOGIN_VALUE_USERNAME'); ?></label>
						</span>
						<input id="modlgn-username" type="text" name="username" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('MOD_JLV_FACEBOOKLOGIN_VALUE_USERNAME') ?>" />
					</div>
				<?php else: ?>
					<label for="modlgn-username"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label>
						<input id="modlgn-username" type="text" name="username" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('MOD_JLV_FACEBOOKLOGIN_VALUE_USERNAME') ?>" />
				<?php endif; ?>
			</div>
		</div>
		<div id="form-login-password" class="control-group">
			<div class="controls">
				<?php if (!$params->get('usetext')) : ?>
					<div class="input-prepend input-append">
						<span class="add-on">
							<span class="icon-lock tip" title="<?php echo JText::_('JGLOBAL_PASSWORD') ?>">
							</span>
								<label for="modlgn-passwd" class="element-invisible"><?php echo JText::_('JGLOBAL_PASSWORD'); ?>
							</label>
						</span>
						<input id="modlgn-passwd" type="password" name="password" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
				</div>
				<?php else: ?>
					<label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
					<input id="modlgn-passwd" type="password" name="password" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
				<?php endif; ?>

			</div>
		</div>
		<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
		<div id="form-login-remember" class="control-group checkbox">
			<label for="modlgn-remember" class="control-label"><?php echo JText::_('MOD_JLV_FACEBOOKLOGIN_REMEMBER_ME') ?></label> <input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
		</div>
		<?php endif; ?>
		<div id="form-login-submit" class="control-group">
			<div class="controls">
				<button type="submit" tabindex="0" name="Submit" class="small button"><?php echo JText::_('JLOGIN') ?></button>
			</div>
		</div>
		<?php
			$usersConfig = JComponentHelper::getParams('com_users');
			if ($usersConfig->get('allowUserRegistration')) : ?>
			<ul class="side-nav unstyled">
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
					<?php echo JText::_('MOD_JLV_FACEBOOKLOGIN_REGISTER'); ?> <span class="icon-arrow-right"></span></a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
					  <?php echo JText::_('MOD_JLV_FACEBOOKLOGIN_FORGOT_YOUR_USERNAME'); ?></a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('MOD_JLV_FACEBOOKLOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
				</li>
				<li>
					<a href="#" onclick="fblogin();return false;" class="fbloginbutton">
						<?php if($facebookimage!='-1') : ?>
						<img border="0" src="<?php echo JURI::base();?>modules/mod_jlv_facebooklogin/images/<?php echo $facebookimage;?>" />
						<?php else : ?>
							<img border="0" src="<?php echo JURI::base();?>modules/mod_jlv_facebooklogin/images/Small_and_Long.png" />
						<?php endif; ?>
					</a>
			<script>
			  function fblogin() {
				FB.login(function(response) {
					<?php if($loading==1) { ?>
						if (response.authResponse) {
								window.addEvent('domready', function(){
									if( $('facebook-login') ){
										SqueezeBox.initialize();
										SqueezeBox.open( $('facebook-login'), {
											handler: 'adopt',
											shadow: true,
											overlayOpacity: 0.5,
											size: {x: <?php echo $modalwidth; ?>, y: <?php echo $modalheight; ?>},
											onOpen: function(){
												$('facebook-login').setStyle('display', 'block');
											}
										});
									}
								});
							
							
							window.location.href=document.URL+'?fb=login&fb=login';
						}
					<?php } ?>
				}, {scope:'email'});
			  }
			</script>
		</li>

			</ul>
		<?php endif; ?>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.login" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<?php if ($params->get('posttext')) : ?>
		<div class="posttext">
		<p><?php echo $params->get('posttext'); ?></p>
		</div>
		<?php if($loading==1) { ?>
	<div id="facebook-login" style="display:none; text-align:center">
		<?php echo $loading_msg; ?>
	</div>
	<?php } ?>
	<?php endif; ?>
</form>

<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
	FB.init({
	  appId      : '<?php echo $appId; ?>', // App ID
	  status     : true, // check login status
	  cookie     : true, // enable cookies to allow the server to access the session
	  xfbml      : true  // parse XFBML
	});
  };
  // Load the SDK Asynchronously
  (function(d){
	 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	 if (d.getElementById(id)) {return;}
	 js = d.createElement('script'); js.id = id; js.async = true;
	 js.src = "//connect.facebook.net/en_US/all.js";
	 ref.parentNode.insertBefore(js, ref);
   }(document));
</script>

