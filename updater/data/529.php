<?php

  @rename('../config.php', '../data/config.php');
  array_map('unlink', glob("../themes_c/*"));