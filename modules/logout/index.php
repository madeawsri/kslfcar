<?php
	@session_start();
	session_destroy();
  $_fn->redirect($SERVER_PATH_HOME);
