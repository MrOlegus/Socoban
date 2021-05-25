<?php

session_start();

include 'modules/get_main_template.php';
echo get_main_template("main_template.html", "rules_main.html");