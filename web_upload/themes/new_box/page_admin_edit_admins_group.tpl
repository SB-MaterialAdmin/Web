<form action="" method="post">
	<div id="admin-page-content">
	<div id="add-group">
		<h3>Группы админов</h3>
		За дополнительной информацией или помощью наведите курсор мыши на знак вопроса.<br /><br />
		Выберите новые группы, в которые <b>{$group_admin_name}</b> будет включен.<br /><br />
		<table width="90%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
		  <tr>
		    <td valign="middle"><div class="rowdesc">{help_icon title="ВЕБ группа" message="Выберите группу"}Группа ВЕб разрешений</div></td>
		    <td>
		    	<div align="left" id="wadmingroup">
			      	<select name="wg" id="wg" class="select">
				        <option value="-1">Нет групп</option>
				        <optgroup label="Группы" style="font-weight:bold;">
							{foreach from=$web_lst item=wg}
							<option value="{$wg.gid}"{if $wg.gid == $group_admin_id} selected="selected"{/if}>{$wg.name}</option>
							{/foreach}
						</optgroup>
			        </select>
		        </div>
		        <div id="wgroup.msg" class="badentry"></div>
		        </td>
		  </tr>
		  
		  <tr id="nsgroup" valign="top" style="height:5px;overflow:hidden;">
		 </tr>
		 
		 <tr>
		    <td valign="middle"><div class="rowdesc">{help_icon title="Группа серверных разрешений" message="Выберите группу"}Группа серверных разрешений </div></td>
		    <td><div align="left" id="wadmingroup">
		      <select name="sg" id="sg" class="select">
		        <option value="-1">Нет групп</option>
		        
		        <optgroup label="Группы" style="font-weight:bold;">
					{foreach from=$group_lst item=sg}
					<option value="{$sg.id}"{if $sg.id == $server_admin_group_id} selected="selected"{/if}>{$sg.name}</option>
					{/foreach}
				</optgroup>
		        </select>
		        </div>
		        <div id="sgroup.msg" class="badentry"></div>
		        </td>
		  </tr>
		 
		  <tr>
		    <td>&nbsp;</td>
		    <td>
		      {sb_button text="Сохранить" class="ok" id="agroups" submit=true}
		      &nbsp;
		      {sb_button text="Назад" onclick="history.go(-1)" class="cancel" id="aback"}
		      </td>
		  </tr>
		</table>
		</div>
	</div>
</form>
