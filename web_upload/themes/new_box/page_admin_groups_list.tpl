{if NOT $permission_listgroups}
	Доступ запрещен!
{else}
	<h3>Группы</h3>
	Кликните на группе, чтобы просмотреть разрешения. <br /><br />
	
	<!-- Web Admin Groups -->
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="4">
				<table width="100%" cellpadding="0" cellspacing="0" class="front-module-header" class="listtable">
					<tr>
						<td align="left">Группы ВЕБ админов</td>
						<td align="right">Всего: {$web_group_count}</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="40%" height='16' class="listtable_top"><strong>Имя группы</strong></td>
			<td width="25%" height='16' class="listtable_top"><strong>Админы в группе</strong></td>
			<td width="30%" height='16' class="listtable_top"><strong>Действия</strong></td>
		</tr>
		{foreach from="$web_group_list" item="group" name="web_group"}
			<tr id="gid_{$group.gid}" class="opener tbl_out" onmouseout="this.className='tbl_out'" onmouseover="this.className='tbl_hover'">
				<td class="listtable_1" height='16'>{$group.name}</td>
		      	<td class="listtable_1" height='16'>{$web_admins[$smarty.foreach.web_group.index]}</td>
				<td class="listtable_1" height='16'> 
					{if $permission_editgroup}
			        	<a href="index.php?p=admin&c=groups&o=edit&type=web&id={$group.gid}">Редактировать</a>
			        {/if}
			        {if $permission_deletegroup}
			            - <a href="#" onclick="RemoveGroup({$group.gid}, '{$group.name}', 'web');">Удалить</a>
					{/if}
				</td>
			</tr>
			<tr>	 
		    	<td colspan="7" align="center">     	
		      	<div class="opener"> 
					<table width="80%" cellspacing="0" cellpadding="0" class="listtable">
		          		<tr>
		            		<td height="16" align="left" class="listtable_top" colspan="3">
								<b>Детали группы</b>            
							</td>
		          		</tr>
		          		<tr align="left">
		            		<td width="20%" height="16" class="listtable_1">Разрешения</td>
		            		<td height="16" class="listtable_1">{$group.permissions}</td>
		           		</tr>
						<tr align="left">
		            		<td width="20%" height="16" class="listtable_1">Участники</td>
		            		<td height="16" class="listtable_1">
								<table width="100%" cellspacing="0" cellpadding="0" class="listtable">
								{foreach from=$web_admins_list[$smarty.foreach.web_group.index] item="web_admin"}
									<tr>
										<td width="60%" height="16" class="listtable_1">{$web_admin.user}</td>
										{if $permission_editadmin}
										<td width="20%" height="16" class="listtable_1"><a href="index.php?p=admin&c=admins&o=editgroup&id={$web_admin.aid}" title="Редактировать группу">Редактировать</a></td>
										<td width="20%" height="16" class="listtable_1"><a href="index.php?p=admin&c=admins&o=editgroup&id={$web_admin.aid}&wg=" title="Удалить из группы">Удалить</a></td>
										{/if}
									</tr>
								{/foreach}
								</table>
							</td>
		           		</tr>
		        	</table>		
		     	</div>
		    </td> 	
		</tr>        
		{/foreach}
	</table>
	<br /><br />
	
	<!-- Server Admin Groups -->
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="4">
			<table width="100%" cellpadding="0" cellspacing="0" class="front-module-header" class="listtable">
				<tr>
					<td align="left">Сереврные группы админов</td>
					<td align="right">Всего: {$server_admin_group_count}</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="40%" height='16' class="listtable_top"><strong>Имя группы</strong></td>
      	<td width="25%" height='16' class="listtable_top"><strong>Админы в группе</strong></td>
		<td width="30%" height='16' class="listtable_top"><strong>Действие</strong></td>
	</tr>
	{foreach from="$server_group_list" item="group" name="server_admin_group"}
		<tr id="gid_{$group.id}" class="opener tbl_out" onmouseout="this.className='tbl_out'" onmouseover="this.setProperty('class', 'tbl_hover')">
			<td class="listtable_1" height='16'>{$group.name}</td>
	      	<td class="listtable_1" height='16'>{$server_admins[$smarty.foreach.server_admin_group.index]}</td>
	        <td class="listtable_1" height='16'> 
				{if $permission_editgroup}
					<a href="index.php?p=admin&c=groups&o=edit&type=srv&id={$group.id}">Редактировать</a>
				{/if}
				{if $permission_deletegroup}
					- <a href="#" onclick="RemoveGroup({$group.id}, '{$group.name}', 'srv');">Удалить</a>
				{/if}
			</td>
		</tr>
		<tr>	 
    		<td colspan="7" align="center">     	
      			<div class="opener"> 
					<table width="80%" cellspacing="0" cellpadding="0" class="listtable">
          				<tr>
            				<td height="16" align="left" class="listtable_top" colspan="3">
								<b>Детали группы</b>            
							</td>
	          			</tr>
	          			<tr align="left">
	            			<td width="20%" height="16" class="listtable_1">Разрешения</td>
	            			<td height="16" class="listtable_1">{$group.permissions}</td>
	           			</tr>
						<tr align="left">
		            		<td width="20%" height="16" class="listtable_1">Участники</td>
		            		<td height="16" class="listtable_1">
								<table width="100%" cellspacing="0" cellpadding="0" class="listtable">
								{foreach from=$server_admins_list[$smarty.foreach.server_admin_group.index] item="server_admin"}
									<tr>
										<td width="60%" height="16" class="listtable_1">{$server_admin.user}</td>
										{if $permission_editadmin}
										<td width="20%" height="16" class="listtable_1"><a href="index.php?p=admin&c=admins&o=editgroup&id={$server_admin.aid}" title="Редактировать группу">Редактировать</a></td>
										<td width="20%" height="16" class="listtable_1"><a href="index.php?p=admin&c=admins&o=editgroup&id={$server_admin.aid}&sg=" title="Удалить из группы">Удалить</a></td>
										{/if}
									</tr>
								{/foreach}
								</table>
							</td>
		           		</tr>
							<tr align="left">
		            <td width="20%" height="16" class="listtable_1">Переназначения</td>
		            <td height="16" class="listtable_1">
									<table width="100%" cellspacing="0" cellpadding="0" class="listtable">
										<tr>
											<td class="listtable_top">Тип</td>
											<td class="listtable_top">Имя</td>
											<td class="listtable_top">Доступ</td>
										</tr>
										{foreach from=$server_overrides_list[$smarty.foreach.server_admin_group.index] item="override"}
										<tr>
											<td width="60%" height="16" class="listtable_1">{$override.type}</td>
											<td width="60%" height="16" class="listtable_1">{$override.name|htmlspecialchars}</td>
											<td width="60%" height="16" class="listtable_1">{$override.access}</td>
										</tr>
										{/foreach}
									</table>
								</td>
		           </tr>
	        	</table>		
	     		</div>
	     	</td> 	
	  	</tr>      
	{/foreach}
	</table>
	<br /><br />


	<!-- Server Groups -->
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="4">
				<table width="100%" cellpadding="0" cellspacing="0" class="front-module-header">
					<tr>
						<td align="left">Группы серверов</td>
						<td align="right">Всего: {$server_group_count}</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="37%" height='16' class="listtable_top"><strong>Имя группы</strong></td>
			<td width="25%" height='16' class="listtable_top"><strong>Сервера в группе</strong></td>
			<td width="30%" height='16' class="listtable_top"><strong>Действие</strong></td>
		</tr>
		{foreach from="$server_list" item="group" name="server_group"}
			<tr id="gid_{$group.gid}" class="opener tbl_out" onmouseout="this.className='tbl_out'" onmouseover="this.setProperty('class', 'tbl_hover')">
	            <td class="listtable_1" height='16'>{$group.name}</td>
	      		<td class="listtable_1" height='16'>{$server_counts[$smarty.foreach.server_group.index]}</td>
	            <td class="listtable_1" height='16'>   
	            {if $permission_editgroup}
					<a href="index.php?p=admin&c=groups&o=edit&type=server&id={$group.gid}">Редактировать</a>
				{/if}
				{if $permission_deletegroup}
					- <a href="#" onclick="RemoveGroup({$group.gid}, '{$group.name}', 'server');">Удалить</a>
				{/if}        
	            </td>
			</tr>
			<tr>	 
	    		<td colspan="7" align="center">     	
	      			<div class="opener"> 
						<table width="80%" cellspacing="0" cellpadding="0" class="listtable">
	          				<tr>
	            				<td height="16" align="left" class="listtable_top" colspan="3"><b>Сервера в группе</b></td>
	          				</tr>
	          				<tr align="left">
	            				<td width="20%" height="16" class="listtable_1">Названия серверов</td>
	            				<td height="16" class="listtable_1" id="servers_{$group.gid}">
	            					Подождите!
		            			</td>
	           				</tr>
	        			</table>		
	     			</div>
	     		</td> 	
	  		</tr> 
		{/foreach}
	</table>
{/if}
