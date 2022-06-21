function RunCron(Token) {
  jQuery.ajax({
    url:   'job.php',
    async: true,
    cache: false,
    data:  {
      token: Token
    },
    dataType: "json",
    method: "GET",
    success: function( response, status, jqXHR ) {
      if (response.result == false) {
        console.error('Failed when CRON run: ' + response.reason);
        return;
      }

      if (response.more == true)
        RunCron(response.token);
    }
  });
}