<?php
/* Smarty version 3.1.36, created on 2023-10-22 23:45:21
  from '/home4/adxventure/hosting.adxventure.com/templates/twenty-one/password-reset-container.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_6535b411a59904_94319319',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'eac438e94b5ebea69c81fe3862be02c00436815b' => 
    array (
      0 => '/home4/adxventure/hosting.adxventure.com/templates/twenty-one/password-reset-container.tpl',
      1 => 1679580036,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6535b411a59904_94319319 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="row justify-content-center">
    <div class="card mw-540 mb-4 mt-4">
        <div class="card-body px-md-5 py-5">
            <?php if ($_smarty_tpl->tpl_vars['loggedin']->value && $_smarty_tpl->tpl_vars['innerTemplate']->value) {?>
                <?php ob_start();
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0], array( array('key'=>'noPasswordResetWhenLoggedIn'),$_smarty_tpl ) );
$_prefixVariable1=ob_get_clean();
$_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['template']->value)."/includes/alert.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('type'=>"error",'msg'=>$_prefixVariable1,'textcenter'=>true), 0, true);
?>
            <?php } else { ?>
                <?php if ($_smarty_tpl->tpl_vars['successMessage']->value) {?>
                    <?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['template']->value)."/includes/alert.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('type'=>"success",'msg'=>$_smarty_tpl->tpl_vars['successTitle']->value,'textcenter'=>true), 0, true);
?>
                    <p><?php echo $_smarty_tpl->tpl_vars['successMessage']->value;?>
</p>
                <?php } else { ?>
                    <?php if ($_smarty_tpl->tpl_vars['innerTemplate']->value) {?>
                        <?php $_smarty_tpl->_subTemplateRender(((string)$_smarty_tpl->tpl_vars['template']->value)."/password-reset-".((string)$_smarty_tpl->tpl_vars['innerTemplate']->value).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
                    <?php }?>
                <?php }?>
            <?php }?>
        </div>
    </div>
</div>
<?php }
}
