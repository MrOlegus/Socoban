<?php

session_start();

includek 'modules/get_main_template.php';
echo get_main_template("main_template.html", "rules_main.html");