<?php 

$USEDBREPLICATE = 1;
$DBCONNECION_REQUIRED = 1;

//define('GLPI_ROOT', '../../../..');
//include(GLPI_ROOT."/inc/includes.php");
//este es una copia de otro reporte
include ("../../../../inc/includes.php");

$report = new PluginReportsAutoReport(__('Prueba_report_title','reports'));

// esta criteria es para poder mostrar la categoria
//new PluginReportsTicketCategoryCriteria($report);

// esta criteria es el ejemplo de glpi criterias segun el campo date_mos
new PluginReportsDateIntervalCriteria($report, "`glpi_logs`.`date_mod`");
$report -> displayCriteriasForm();

$report->setColumns(array(new PluginReportsColumn('completename', $LANG["entity"][0]),
                          new PluginReportsColumnLink('groupid', $LANG["common"][35], 'Group'),
                          new PluginReportsColumnLink('userid', $LANG["setup"][18], 'User'),
                          new PluginReportsColumn('firstname', $LANG["common"][43]),
                          new PluginReportsColumn('realname', $LANG["common"][48]),
                          new PluginReportsColumnDateTime('last_login', $LANG['login'][0])));

$query = "SELECT `glpi_entities`.`completename`,
                 `glpi_groups`.`id` AS groupid,
                 `glpi_users`.`id` AS userid,
                 `glpi_users`.`firstname`,
                 `glpi_users`.`realname`,
                 `glpi_users`.`last_login`
          FROM `glpi_groups`
          LEFT JOIN `glpi_groups_users` ON (`glpi_groups_users`.`groups_id` = `glpi_groups`.`id`)
          LEFT JOIN `glpi_users` ON (`glpi_groups_users`.`users_id` = `glpi_users`.`id`
                                     AND `glpi_users`.`is_deleted` = '0' )
          LEFT JOIN `glpi_entities` ON (`glpi_groups`.`entities_id` = `glpi_entities`.`id`)".
          getEntitiesRestrictRequest(" WHERE ", "glpi_groups") ." 
          ORDER BY `completename`, `glpi_groups`.`name`, `glpi_users`.`name`";

$report->setGroupBy(array('completename',
                          'groupid'));

$report->setSqlRequest($query);

$report->execute();