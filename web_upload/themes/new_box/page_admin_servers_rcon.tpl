-{if NOT $permission_rcon}-
	Access Denied!
-{else}-
<div class="card m-b-0" id="admin-page-content">
                        
                            <div class="listview lv-message" id="rcon">

									<div id="rcon_con" class="p-b-20">
										<div class="lv-item media">
											<div class="lv-avatar bgm-red pull-left">R</div>
											<div class="media-body">
												<div class="ms-item" style="display: block;max-width: 100%;">
													************************************************************<br />*&nbsp;SourceBans RCON console<br />*&nbsp;Type your comand in the box below and hit enter<br />*&nbsp;Type 'clr' to clear the console<br />************************************************************
												</div>
											</div>
										</div>
									</div>
                                
                                <div class="lv-footer ms-reply">
                                    <textarea id="cmd" placeholder="Комманда на выполнение...."></textarea>
                                    <button onclick="SendRcon();" id="rcon_btn"><i class="zmdi zmdi-mail-send"></i></button>
                                </div>
                            </div>
                    </div>
					
<script>

$E('html').onkeydown = function(event){
    var event = new Event(event);
    if (event.key == 'enter' ) SendRcon();
};

function SendRcon()
{
	xajax_SendRcon('-{$id}-', $('cmd').value, true);
	 $('cmd').value='Выполняю, пожалуйста, подождите...'; $('cmd').disabled='true'; $('rcon_btn').disabled='true';
	 
}
</script>
-{/if}-
