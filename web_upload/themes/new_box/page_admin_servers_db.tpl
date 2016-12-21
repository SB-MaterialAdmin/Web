<div class="card">
    <div class="card-header">
        <h2>Данные для доступа к базе данных SourceBans</h2>
    </div>
        <div class="alert alert-info" role="alert">
            Скопируйте это в databases.cfg Вашего сервера: <b>/[mod]/addons/sourcemod/configs/databases.cfg</b>
        </div>
        <div class="card-body card-padding">
            <div class="form-group">
                <div class="fg-line">
                    <textarea class="form-control" rows="23" readonly>{$conf}</textarea>
                </div>
            </div>
        </div>
        <div class="card-body card-padding text-center">{sb_button text="Назад" onclick="history.go(-1)" icon="<i class='zmdi zmdi-undo'></i>" class="bgm-red btn-icon-text" id="aconf" submit=false}
        </div>
</div>


