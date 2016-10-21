<?php 
if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();}
?>

<div class="col-xs-6 p-b-10">
	<a data-toggle="modal" href="#modal_srv" class="btn bgm-blue btn-block waves-effect">Настроить Серверную группу</a>
</div>

<div class="modal fade" id="modal_srv" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">{title}</h4>
			</div>
			<div class="modal-body">
				
				<div class="card">
				<div class="card-body table-responsive">
				<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table">
				  <thead>
					  <tr>
						<th colspan="2" class="tablerow4">Name</th>
						<th class="tablerow4">Flag</th>
						<th colspan="2" class="tablerow4">Purpose</th>
					  </tr>
				  </thead>
				  <tr id="srootcheckbox" name="srootcheckbox">
					<td colspan="2" class="tablerow2">Root Admin (Full Admin Access)</td>
					<td class="tablerow2" align="center">z</td>
					<td class="tablerow2"> Magically enables all flags.</td>
					<td align="center" class="tablerow2"><input type="checkbox" name="s14" id="s14" /></td>
				  </tr>
				  <tr>
					<th colspan="5" class="tablerow4">Standard Admin Server Permissions </th>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Reserved Slots </td>
					<td class="tablerow1" align="center">a</td>
					<td class="tablerow1"> Reserved slot access.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s1" id="s1" value="1" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Generic</td>
					<td class="tablerow1" align="center">b</td>
					<td class="tablerow1"> Generic admin; required for admins.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s23" id="s23" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Kick Players </td>
					<td class="tablerow1" align="center">c</td>
					<td class="tablerow1"> Kick other players.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s2" id="s2" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Ban Players </td>
					<td class="tablerow1" align="center">d</td>
					<td class="tablerow1"> Ban other players.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s3" id="s3" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Unban Players </td>
					<td align="center" class="tablerow1">e</td>
					<td class="tablerow1"> Remove bans.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s4" id="s4" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Slay</td>
					<td align="center" class="tablerow1">f</td>
					<td class="tablerow1"> Slay/harm other players.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s5" id="s5" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Map Changes </td>
					<td align="center" class="tablerow1">g</td>
					<td class="tablerow1"> Change the map or major gameplay features.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s6" id="s6" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Change cvars </td>
					<td align="center" class="tablerow1">h</td>
					<td class="tablerow1"> Change most cvars.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s7" id="s7" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Exec Config Files </td>
					<td class="tablerow1" align="center">i</td>
					<td class="tablerow1"> Execute config files.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s8" id="s8" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Admin Chat  </td>
					<td class="tablerow1" align="center">j</td>
					<td class="tablerow1"> Special chat privileges.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s9" id="s9" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Start Votes </td>
					<td class="tablerow1" align="center">k</td>
					<td class="tablerow1"> Start or create votes.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s10" id="s10" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Password Server </td>
					<td class="tablerow1" align="center">l</td>
					<td class="tablerow1"> Set a password on the server.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s11" id="s11" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Run RCON Commands </td>
					<td class="tablerow1" align="center">m</td>
					<td class="tablerow1"> Use RCON commands.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s12" id="s12" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Enable Cheats </td>
					<td class="tablerow1" align="center">n</td>
					<td class="tablerow1"> Change sv_cheats or use cheating commands.</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s13" id="s13" /></td>
				  </tr>
				  <tr>
					<th colspan="5" class="tablerow4">Immunity </th>
				  </tr>
				  <tr class="tablerow1">
					<td width="15%">&nbsp;</td>
					<td class="tablerow1">Immunity </td>
					<td class="tablerow1" align="center"></td>
					<td class="tablerow1">Choose the immunity level. The higher the number, the more immunity.<br /><div align="center"><input type="text" width="5" name="immunity" id="immunity" /></div></td>
					<td align="center" class="tablerow1"></td>
				  </tr>
				  <tr>
					<th colspan="5" class="tablerow4">Custom Admin Server Permissions </th>
				  </tr>
				  <tr class="tablerow1">
					<td>&nbsp;</td>
					<td class="tablerow1">Custom flag &quot;o&quot;  </td>
					<td class="tablerow1" align="center">o</td>
					<td class="tablerow1">&nbsp;</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s17" id="s17" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td>&nbsp;</td>
					<td class="tablerow1">Custom flag &quot;p&quot; </td>
					<td class="tablerow1" align="center">p</td>
					<td class="tablerow1">&nbsp;</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s18" id="s18" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td>&nbsp;</td>
					<td class="tablerow1">Custom flag &quot;q&quot; </td>
					<td class="tablerow1" align="center">q</td>
					<td class="tablerow1">&nbsp;</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s19" id="s19" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td>&nbsp;</td>
					<td class="tablerow1">Custom flag &quot;r&quot; </td>
					<td class="tablerow1" align="center">r</td>
					<td class="tablerow1">&nbsp;</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s20" id="s20" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td>&nbsp;</td>
					<td class="tablerow1">Custom flag &quot;s&quot; </td>
					<td class="tablerow1" align="center">s</td>
					<td class="tablerow1">&nbsp;</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s21" id="s21" /></td>
				  </tr>
				  <tr class="tablerow1">
					<td>&nbsp;</td>
					<td class="tablerow1">Custom flag &quot;t&quot; </td>
					<td class="tablerow1" align="center">t</td>
					<td class="tablerow1">&nbsp;</td>
					<td align="center" class="tablerow1"><input type="checkbox" name="s22" id="s22" /></td>
				  </tr>
				</table>
				</div>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Готово</button>
			</div>
		</div>
	</div>
</div>