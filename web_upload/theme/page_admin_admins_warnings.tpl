<div class="card-header">
	<h2>Список предупреждений
		<small>Всего: {$count}</small>
	</h2>
</div>
<div class="table-responsive">
	<table cellspacing="0" cellpadding="0" class="table table-striped">
		<tr>
			<th width="8%">ID</th>
			<th>От кого</th>
			<th class="text-right">Причина</th>
			<th class="text-right">Истекает</th>
			<th style="width: 12%;" class="text-right">Действия</th>
		</tr>
		
		{foreach from="$Warnings" item="warning"}
		<tr{if $warning.expired} class="warning"{/if}>
			<td>{$warning.id}</td>
			<td><strong>{$warning.from|escape}</strong></td>
			<td class="text-right">{$warning.reason|escape}</td>
			<td class="text-right">{$warning.expires}</td>
			<td class="text-right">{if $warning.expired}Недоступно{else}{assign var="warId" value=$warning.id}{sb_button text="Снять" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-red btn-icon-text" onclick="xajax_RemoveWarning($warId)"}{/if}</td>
		</tr>
		{/foreach}
	</table>
</div>

<div class="card">
	<div class="form-horizontal" role="form" id="add-group">
		<div class="card-header">
			<h2>Выдача предупреждения</h2>
		</div>
		<div class="card-body card-padding p-b-0">
			<div class="form-group form-inline fg-float">
				<div class="col-sm-2">
					<div class="fg-line">
						<input class="input-sm form-control fg-input" type="text" id="time" name="time" style="width: 100%;">
						<label class="fg-label">Срок (в днях)</label>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="fg-line">
						<input class="input-sm form-control fg-input" type="text" id="reason" name="reason" style="width: 100%;">
						<label class="fg-label">Причина</label>
					</div>
				</div>
				<div class="col-sm-2">
					{sb_button text="Добавить" icon="<i class='zmdi zmdi-check-all'></i>" class="bgm-orange btn-icon-text" onclick="xajax_AddWarning($thisId, $('time').value, $('reason').value);" submit=false}
				</div>
			</div>
		</div>
		<br />
	</div>
</div>
