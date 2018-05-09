<?php
/**
 * @version $Id: histoinst.php 357 2018-03-16 16:17:52Z yllen $
 -------------------------------------------------------------------------
  LICENSE

 This file is part of Reports plugin for GLPI.

 Reports is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 Reports is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with Reports. If not, see <http://www.gnu.org/licenses/>.

 @package   reports
 @authors    Nelly Mahu-Lasson, Remi Collet
 @copyright Copyright (c) 2009-2018 Reports plugin team
 @license   AGPL License 3.0 or (at your option) any later version
            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 @link      https://forge.glpi-project.org/pojects/reports
 @link      http://www.glpi-project.org/
 @since     2009
 --------------------------------------------------------------------------
 */

$USEDBREPLICATE        = 1;
$DBCONNECTION_REQUIRED = 1;

include ("../../../../inc/includes.php");

// esta para declarar un nuevo reporte
$report = new PluginReportsAutoReport(__('reportePrueba_report_title','reports'));

//Llamada local al nombre del reporte
includeLocales("reportePrueba");
includeLocales("histoinst");

//Session::checkRight("plugin_reports_histoinst", READ);
$computer = new Computer();
$computer->checkGlobal(READ);
$software = new Software();
$software->checkGlobal(READ);

// Cabecera de Inicio, solo muestra la parte de INICIO > HERRAMIENTAS > INFORMES......
Html::header(__('Reporte_de_Prueba', 'reports'), $_SERVER['PHP_SELF'], "utils", "report");

//new PluginReportsDateIntervalCriteria($report, "date_mod"); new
new PluginReportsDateIntervalCriteria($report, '`glpi_tickets`.`date_mod`');

$report -> displayCriteriasForm();  

if($report -> criteriasValidated())
{
  $report -> setSubNameAuto();
  // AQUI ESTAN LOS NOMBRES DE LOS CAMPOS A MOSTRAR
  $report -> setColumns([
    new PluginReportsColumn('id', __('ID')), 
    new PluginReportsColumn('name', __('Titulo')), 
    new PluginReportsColumn('date', __('Fecha de Apertura')),
    new PluginReportsColumn('solicitante', __('Solicitante')), 
    new PluginReportsColumn('asignado', __('Asignado a')), 
    new PluginReportsColumn('categoria', __('Categoria')),
    new PluginReportsColumnMap('type', __('Tipo')), 
    new PluginReportsColumnMap('urgency', __('Urgencia')), 
    new PluginReportsColumnMap('priority', __('Prioridad')), 
    new PluginReportsColumn('date_mod', __('Fecha de Modificacion'))
    ]);

    $query="SELECT 
      `glpi_tickets`.`id`,
      `glpi_tickets`.`name`,
      `glpi_tickets`.`date`,
      CONCAT(`glpi_users`.`realname`,' ',`glpi_users`.`firstname`) AS solicitante,
      CONCAT(`u`.`realname`,' ',`u`.`firstname`) AS asignado, 
      `glpi_itilcategories`.`completename` AS categoria,
      `glpi_tickets`.`type`,
      `glpi_tickets`.`urgency`,
      `glpi_tickets`.`priority`,
      `glpi_tickets`.`date_mod`
    FROM `glpi_tickets`
    JOIN `glpi_users` ON (`glpi_tickets`.`users_id_lastupdater` = `glpi_users`.`id`)
    JOIN `glpi_users` AS `u` ON (`glpi_tickets`.`users_id_recipient`=`u`.`id`)
    JOIN `glpi_itilcategories` ON (`glpi_tickets`.`itilcategories_id`=`glpi_itilcategories`.`id`)".
    $report->addSqlCriteriasRestriction("WHERE")." AND `glpi_tickets`.`solvedate` IS NULL";
    
  $report->setSqlRequest($query);

  $report->execute();
}

Html::footer();