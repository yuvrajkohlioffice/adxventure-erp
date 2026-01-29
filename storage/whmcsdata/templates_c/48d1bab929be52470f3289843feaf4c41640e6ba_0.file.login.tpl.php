<?php
/* Smarty version 3.1.36, created on 2023-10-22 23:45:22
  from '/home4/adxventure/hosting.adxventure.com/templates/twenty-one/login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_6535b4127afc50_84955772',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '48d1bab929be52470f3289843feaf4c41640e6ba' => 
    array (
      0 => '/home4/adxventure/hosting.adxventure.com/templates/twenty-one/login.tpl',
      1 => 1679580036,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6535b4127afc50_84955772 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="providerLinkingFeedback"></div>

<form method="post" action="<?php echo routePath('login-validate');?>
" class="login-form" role="form">
    <div class="card mw-540 mb-md-4 mt-md-4">
        <div class="card-body px-sm-5 py-5">
            <div class="mb-4">
                <h6 class="h3"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0], array( array('key'=>'loginbutton'),$_smarty_tpl ) );?>
</h6>
                <p class="text-muted mb-0"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0], array( array('key'=>'userLogin.signInToContinue'),$_smarty_tpl ) );?>
</p>
            </div>
            <?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['template']->value)."/includes/flashmessage.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
            <div class="form-group">
                <label for="inputEmail" class="form-control-label"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0], array( array('key'=>'clientareaemail'),$_smarty_tpl ) );?>
</label>
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="email" class="form-control" name="username" id="inputEmail" placeholder="name@example.com" autofocus>
                </div>
            </div>
            <div class="form-group mb-4 focused">
                <div class="d-flex align-items-center justify-content-between">
                    <label for="inputPassword" class="form-control-label"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0], array( array('key'=>'clientareapassword'),$_smarty_tpl ) );?>
</label>
                    <div class="mb-2">
                        <a href="<?php echo routePath('password-reset-begin');?>
" class="small text-muted" tabindex="-1"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0], array( array('key'=>'forgotpw'),$_smarty_tpl ) );?>
</a>
                    </div>
                </div>
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                    </div>
                    <input type="password" class="form-control pw-input" name="password" id="inputPassword" placeholder="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0], array( array('key'=>'clientareapassword'),$_smarty_tpl ) );?>
" autocomplete="off">
                    <div class="input-group-append">
                        <button class="btn btn-default btn-reveal-pw" type="button" tabindex="-1">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php if ($_smarty_tpl->tpl_vars['captcha']->value->isEnabled()) {?>
                <?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['template']->value)."/includes/captcha.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
            <?php }?>
            <div class="float-left">
                <button id="login" type="submit" class="btn btn-primary<?php echo $_smarty_tpl->tpl_vars['captcha']->value->getButtonClass($_smarty_tpl->tpl_vars['captchaForm']->value);?>
">
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0], array( array('key'=>'loginbutton'),$_smarty_tpl ) );?>

                </button>
            </div>
            <div class="text-right">
                <label>
                    <input type="checkbox" class="form-check-input" name="rememberme" />
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0], array( array('key'=>'loginrememberme'),$_smarty_tpl ) );?>

                </label>
            </div>
        </div>
        <div class="card-footer px-md-5">
            <small><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0], array( array('key'=>'userLogin.notRegistered'),$_smarty_tpl ) );?>
</small>
            <a href="<?php echo $_smarty_tpl->tpl_vars['WEB_ROOT']->value;?>
/register.php" class="small font-weight-bold"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0], array( array('key'=>'userLogin.createAccount'),$_smarty_tpl ) );?>
</a>
        </div>
    </div>
</form>

<?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['template']->value)."/includes/linkedaccounts.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('linkContext'=>"login",'customFeedback'=>true), 0, true);
}
}
