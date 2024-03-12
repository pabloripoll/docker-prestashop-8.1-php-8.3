<?php
/* Smarty version 4.3.4, created on 2024-03-12 11:46:39
  from '/var/www/html/admin1397ire1dpgxdkjyelq/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_65f0409fab38a2_52102237',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4df50c96407d23f8b93a0eaf47ee924eb7bd8880' => 
    array (
      0 => '/var/www/html/admin1397ire1dpgxdkjyelq/themes/default/template/content.tpl',
      1 => 1707478622,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65f0409fab38a2_52102237 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>
<div id="content-message-box"></div>

<?php if ((isset($_smarty_tpl->tpl_vars['content']->value))) {?>
	<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

<?php }
}
}
