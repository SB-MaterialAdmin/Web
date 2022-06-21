<div class="card">
  <div class="card-header">
    <h2>Отправить E-Mail
      <small>
        SteamID Игрока: <b>{$email_addr}</b>
      </small>
    </h2>
  </div>  
  <div class="card-body card-padding p-b-0">
    <div class="form-group m-b-5">
      <label for="theme" class="col-sm-3 control-label">{help_icon title="Тема" message="Введите тему сообщения."} Тема</label>
      <div class="col-sm-9">
        <div class="fg-line">
          <textarea class="form-control p-t-5 textbox" id="subject" name="subject" placeholder="Введите название темы.">{$comment}</textarea>
        </div>
        <div id="subject.msg" class="badentry"></div>
      </div>
    </div>
    <div class="form-group m-b-5">
      <label for="message" class="col-sm-3 control-label">{help_icon title="Тема" message="Введите тему сообщения."} Тема</label>
      <div class="col-sm-9">
        <div class="fg-line">
          <textarea class="form-control p-t-5 textbox" rows="3" id="message" name="message" placeholder="Введите сообщение.">{$comment}</textarea>
        </div>
        <div id="message.msg" class="badentry"></div>
      </div>
    </div>
  </div>
  <div class="card-body card-padding text-center" style="margin-top: 10%;">
    {sb_button text="Отправить E-mail" onclick="$email_js" icon="<i class='zmdi zmdi-check-all'></i>" class="ok btn bgm-green btn-icon-text waves-effect" id="aemail" submit=false}
    &nbsp;
    {sb_button text="Назад" onclick="history.go(-1)" icon="<i class='zmdi zmdi-undo'></i>" class="cancel bgm-red btn-icon-text" id="back" submit=false}
  </div>
</div>