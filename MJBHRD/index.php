<?PHP
include 'main/koneksi.php';
session_start();
//echo APP;
 if(isset($_SESSION[APP]['user_id']) || isset($_SESSION[APP]['username']) || isset($_SESSION[APP]['emp_id']) || isset($_SESSION[APP]['emp_name']) || isset($_SESSION[APP]['io_id']) || isset($_SESSION[APP]['io_name']) || isset($_SESSION[APP]['loc_id']) || isset($_SESSION[APP]['loc_name']) || isset($_SESSION[APP]['org_id']) || isset($_SESSION[APP]['org_name']))
  {
	unset($_SESSION[APP]['user_id']);
	unset($_SESSION[APP]['username']);
	unset($_SESSION[APP]['emp_id']);
	unset($_SESSION[APP]['emp_name']);
	unset($_SESSION[APP]['io_id']);
	unset($_SESSION[APP]['io_name']);
	unset($_SESSION[APP]['loc_id']);
	unset($_SESSION[APP]['loc_name']);
	unset($_SESSION[APP]['org_id']);
	unset($_SESSION[APP]['org_name']);
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by TEMPLATED
http://templated.co
Released for free under the Creative Commons Attribution License

Name       : Big Business 2.0
Description: A two-column, fixed-width design with a bright color scheme.
Version    : 1.0
Released   : 20120624
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="description" content="" />
<meta name="keywords" content="" />
<title>PT. Merak Jaya Group</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="media/css/cssphplama.css" />
<script type="text/javascript" src="media/js/jquery.min.js"></script>
<script type="text/javascript" src="media/js/jquery.dropotron-1.0.js"></script>
<script type="text/javascript" src="media/js/jquery.slidertron-1.1.js"></script>

<link rel="stylesheet" type="text/css" href= <?PHP echo PATHAPP . "/media/css/cssphpbaru.css"?> />
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/jquery.min.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/jquery.dropotron-1.0.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/jquery.slidertron-1.1.js"?> ></script>

<link rel="stylesheet" href= <?PHP echo PATHAPP . "/media/css/ext-all.css"?> />
<link rel="stylesheet" href= <?PHP echo PATHAPP . "/media/css/icons.css"?> />
<link rel="stylesheet" href= <?PHP echo PATHAPP . "/media/css/app.css"?> />	
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/ext-all.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/exporter/Exporter-all.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/exporter/Base64.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/locale/ext-lang-id.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/ux/GroupSummary.js"?> ></script>
<script type="text/javascript" src= <?PHP echo PATHAPP . "/media/js/app.js"?> ></script>

<script type="text/javascript">
	$(function() {
		$('#menu > ul').dropotron({
			mode: 'fade',
			globalOffsetY: 11,
			offsetY: -15
		});
	});
</script>


<script type="text/javascript">
Ext.Loader.setConfig({enabled: true});

Ext.Loader.setPath('<?PHP echo PATHAPP ?>/media/js/ux/');

Ext.require([
    'Ext.grid.*',
    'Ext.panel.*',
	'Ext.data.*',
	'Ext.util.*',
    'Ext.tip.QuickTipManager',
	'Ext.form.field.Number',
	'Ext.selection.CheckboxModel',
	'Ext.toolbar.Paging',
    'Ext.ModelManager',
	'Ext.form.*',
    'Ext.layout.container.Column',
    'Ext.state.*',
]);

Ext.onReady(function () {
		Ext.tip.QuickTipManager.init();
		Ext.Loader.injectScriptElement(
			'<?PHP echo PATHAPP ?>/media/js/locale/ext-lang-id.js',
			setupApp,
			Ext.emptyFn,
			this,
			'utf-8'
		);
});

function setupApp(){
	Ext.QuickTips.init();	
	var currentTime = new Date();
	var contentPanel = Ext.create('Ext.panel.Panel',{
			bodyStyle: 'spacing: 10px;border:none',
			items:[{
				xtype:'label',
				html:'<div align="left"><font size="5"><b>Program HRD</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'label',
				html:'<div align="left"><font size="2">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspSelamat datang pada program HRD. Program ini berfungsi untuk melakukan pembuatan surat ijin, cuti, atau sakit. Untuk menggunakan aplikasi ini silahkan login dibawah ini:</font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'label',
				html:'<div align="left"><font size="4"><b>Login</b></font></div>',
			},{
				xtype:'label',
				html:'&nbsp',
			},{
				xtype:'textfield',
				fieldLabel:'Username',
				width:350,
				labelWidth:75,
				id:'tf_username',
				allowBlank: false,
				listeners: {
					 change: function(field,newValue,oldValue){
							field.setValue(newValue.toUpperCase());
					}
				}
			},{
				xtype:'textfield',
				fieldLabel:'Password',
				width:350,
				labelWidth:75,
				inputType: 'password',
				id:'tf_password',
				allowBlank: false,
				listeners: {
					change: function(field,newValue,oldValue){
							field.setValue(newValue.toUpperCase());
					},
					specialkey: function(field, eventObj){
						if (eventObj.getKey() == Ext.EventObject.ENTER) {
							if(Ext.getCmp('tf_username').getValue()!=''){
								if(Ext.getCmp('tf_password').getValue()!=''){
									//console.log('<?php echo PATHAPP . '/main/querylogin.php'; ?>');
									Ext.Ajax.request({
										url:'<?php echo PATHAPP . '/main/querylogin.php'; ?>',
										params:{
											username:Ext.getCmp('tf_username').getValue(),
											userpass:Ext.getCmp('tf_password').getValue(),
										},
										method:'POST',
										success:function(response){
											var json=Ext.decode(response.responseText);
											console.log(json.rows);
											if (json.rows == "sukses"){
												//console.log("<?php echo PATHAPP ?>");
												alertDialog('Success','Welcome ' + Ext.getCmp('tf_username').getValue());
												window.location.href='<?php echo PATHAPP . '/main/indexUtama.php'; ?>'; 
											} else {
												//alertDialog('Warning','Username or Password is not exist.');
												alertDialog('Warning',json.rows);
											}
										},
										failure:function(result,action){
											alertDialog('Warning','Username or Passwordis not exist.');
										}
									});
								} else {
									alertDialog('Warning','Password is empty.');	
								}
							}
							else {
								alertDialog('Warning','Username is empty.');
							}
						}
					}
				}
			},{
				xtype:'panel',
				bodyStyle: 'padding-left: 80px;padding-bottom: 50px;border:none',
				items:[{
					xtype:'button',
					text:'Login',
					width:50,
					handler:function(){
						if(Ext.getCmp('tf_username').getValue()!=''){
							if(Ext.getCmp('tf_password').getValue()!=''){
								
								Ext.Ajax.request({
										url:'<?php echo PATHAPP . '/main/querylogin.php'; ?>',
										params:{
											username:Ext.getCmp('tf_username').getValue(),
											userpass:Ext.getCmp('tf_password').getValue(),
										},
										method:'POST',
										success:function(response){
											var json=Ext.decode(response.responseText);
											console.log(json.rows);
											if (json.rows == "sukses"){
												//console.log("<?php echo PATHAPP ?>");
												alertDialog('Success','Welcome ' + Ext.getCmp('tf_username').getValue());
												window.location.href='<?php echo PATHAPP . '/main/indexUtama.php'; ?>'; 
											} else {
												//alertDialog('Warning','Username or Password is not exist.');
												alertDialog('Warning',json.rows);
											}
										},
										failure:function(result,action){
											alertDialog('Warning',json.rows);
										}
									});
							} else {
								alertDialog('Warning','Password is empty.');	
							}
						}
						else {
							alertDialog('Warning','Username is empty.');
						}
					}
				}]
			}],
		});
   contentPanel.render('page');
}
</script>


<style type="text/css">
<!--
.style3 {
	font-size: 12mm;
	color: #FFFFFF;
}
-->
</style>
</head>
<body>
<div id="wrapper">
	<div id="headerlama">
		<div>
		<table width="884" border="0">
  			<tr>
    			<td width="130" rowspan="2"><img src="images/header.png" alt="" /></td>
   			 	<td width="603"><h1><span class="style3">PT. MERAK JAYA GROUP</span></h1></td>
  			</tr>
			<tr>
    			<td width="603"><h2><span class="style3"></span></h2></td>
    			<td width="137"></td>
  			</tr>
		</table>
		</div>
	</div>
	<div id="menu">
		<ul>
			<li class="first">
				<a href="index.php">Home</a>
			</li>
		</ul>
		<br class="clearfix" />
	</div>
	
		
		
	<div id="page">
	

	</div>
</div>
<div id="footerlama">
	<?PHP include 'main/footer.php'; ?>
</div>
</body>
</html>