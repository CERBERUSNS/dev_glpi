<?php

$dropdown = new PluginFieldsTipofieldDropdown();
$options = [
   'ddtype' => $_GET['ddtype']
];
include GLPI_ROOT . "/front/dropdown.common.form.php";
