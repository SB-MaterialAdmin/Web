{if NOT $permission_import}
	Доступ запрещен!
{else}
    {display_header title="Импорт банов" text="За дополнительной информацией или помощью наведите курсор мыши на знак вопроса."}
    <div class="card-body card-padding p-b-0 clearfix">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="importBans" />
            <div class="form-group m-b-5">
                <label for="file" class="col-sm-3 control-label">{help_icon title="Файл" message="Выберите файл banned_users.cfg или banned_ip.cfg для загрузки и импорта банов."} Файл </label>
                <div class="col-sm-9">
                    <!-- <div class="fg-line">
                        <input type="file" TABINDEX=1 class="file" id="importFile" name="importFile" />
                    </div> -->
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <span class="btn btn-primary btn-file m-r-10 waves-effect">
                            <span class="fileinput-new">Выбрать файл</span>
                            <span class="fileinput-exists">Изменить</span>
                            <input name="importFile" type="file" />
                        </span>
                        <span class="fileinput-filename"></span>
                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput">×</a>
                    </div>
                    <div id="file.msg" class="badentry"></div>
                </div>
            </div>
            {display_material_checkbox name="friendsname" help_title="Получить имена" help_text="Поставьте флажок, если Вы хотите получить имена игроков из их профиля Steam. (работает с  banned_users.cfg)"}
            <br /><br />
            <center>
                {sb_button text="Начать процесс импортирования банов" icon="<i class='zmdi zmdi-account-add'></i>" class="bgm-green btn-icon-text" id="iban" submit=true}
            </center>
            <br />
        </form>
    </div>
    {if !$extreq}
    <script type="text/javascript">
        $('friendsname').disabled = true;
    </script>
    {/if}
{/if}
