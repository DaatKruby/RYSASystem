<?php
global $db_user_name;
global $db_password;
global $db_data_base_name;
$mysqli = new mysqli("localhost", $db_user_name, $db_password, "$db_data_base_name");