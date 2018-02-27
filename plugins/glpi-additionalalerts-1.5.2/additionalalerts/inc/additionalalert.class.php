<?php
/*
 * @version $Id: HEADER 15930 2011-10-30 15:47:55Z tsmr $
 -------------------------------------------------------------------------
 Additionalalerts plugin for GLPI
 Copyright (C) 2003-2011 by the Additionalalerts Development Team.

 https://forge.indepnet.net/projects/additionalalerts
 -------------------------------------------------------------------------

 LICENSE
		
 This file is part of Additionalalerts.

 Additionalalerts is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Additionalalerts is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with additionalalerts. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginAdditionalalertsAdditionalalert extends CommonDBTM {
   
	static function getTypeName() {
      global $LANG;

      return $LANG['plugin_additionalalerts']['title'][1];
   }
   
   function canCreate() {
      return plugin_additionalalerts_haveRight("additionalalerts", 'w');
   }

   function canView() {
      return plugin_additionalalerts_haveRight("additionalalerts", 'r');
   }
   
	static function displayAlerts() {
      global $DB,$LANG,$CFG_GLPI;

      $CronTask=new CronTask();
      
      $config = new PluginAdditionalalertsConfig();
      $config->getFromDB('1');
      
      $infocom = new PluginAdditionalalertsInfocomAlert();
      $infocom->getFromDBbyEntity($_SESSION["glpiactive_entity"]);
      if (isset($infocom->fields["use_infocom_alert"]) 
         && $infocom->fields["use_infocom_alert"] > 0)
         $use_infocom_alert=$infocom->fields["use_infocom_alert"];
      else
         $use_infocom_alert=$config->fields["use_infocom_alert"];
      
      $ocsalert = new PluginAdditionalalertsOcsAlert();
      $ocsalert->getFromDBbyEntity($_SESSION["glpiactive_entity"]);
      if (isset($ocsalert->fields["use_newocs_alert"]) 
         && $ocsalert->fields["use_newocs_alert"] > 0)
         $use_newocs_alert=$ocsalert->fields["use_newocs_alert"];
      else
         $use_newocs_alert=$config->fields["use_newocs_alert"];

      if (isset($ocsalert->fields["delay_ocs"]) 
         && $ocsalert->fields["delay_ocs"] > 0)
         $delay_ocs=$ocsalert->fields["delay_ocs"];
      else
         $delay_ocs=$config->fields["delay_ocs"];
 
      $additionalalerts_ocs=0;
      if ($CronTask->getFromDBbyName("PluginAdditionalalertsOcsAlert","AdditionalalertsOcs")) {
         if ($CronTask->fields["state"]!=CronTask::STATE_DISABLE && $delay_ocs > 0) {
            $additionalalerts_ocs=1;
         }
      }
      $additionalalerts_new_ocs=0;
      if ($CronTask->getFromDBbyName("PluginAdditionalalertsOcsAlert","AdditionalalertsNewOcs")) {
         if ($CronTask->fields["state"]!=CronTask::STATE_DISABLE && $use_newocs_alert > 0) {
            $additionalalerts_new_ocs=1;
         }
      }
      $additionalalerts_not_infocom=0;
      if ($CronTask->getFromDBbyName("PluginAdditionalalertsInfocomAlert","AdditionalalertsNotInfocom")) {
         if ($CronTask->fields["state"]!=CronTask::STATE_DISABLE && $use_infocom_alert > 0) {
            $additionalalerts_not_infocom=1;
         }
      }
      
      if ($additionalalerts_ocs==0 
         && $additionalalerts_new_ocs==0 
         && $additionalalerts_not_infocom==0) {
         echo "<div align='center'><b>".$LANG['plugin_additionalalerts']['setup'][18]."</b></div>";
      }
      if ($additionalalerts_not_infocom!=0) {
         if (Session::haveRight("infocom","w")) {

            $query=PluginAdditionalalertsInfocomAlert::query($_SESSION["glpiactive_entity"]);
            $result = $DB->query($query);

            if ($DB->numrows($result)>0) {

               if (Session::isMultiEntitiesMode()) {
                  $nbcol=7;
               } else {
                  $nbcol=6;
               }
               echo "<div align='center'><table class='tab_cadre' cellspacing='2' cellpadding='3'><tr><th colspan='$nbcol'>";
               echo $LANG['plugin_additionalalerts']['alert'][11]."</th></tr>";
               echo "<tr><th>".$LANG['common'][16]."</th>";
               if (Session::isMultiEntitiesMode())
                  echo "<th>".$LANG['entity'][0]."</th>";
               echo "<th>".$LANG['common'][17]."</th>";
               echo "<th>".$LANG['computers'][9]."</th>";
               echo "<th>".$LANG['state'][0]."</th>";
               echo "<th>".$LANG['common'][15]."</th>";
               echo "<th>".$LANG['common'][34]." / ".$LANG['common'][35]." / ".$LANG['common'][18]."</th></tr>";
               while ($data=$DB->fetch_array($result)) {

                  echo PluginAdditionalalertsInfocomAlert::displayBody($data);
               }
               echo "</table></div>";
            } else {
               echo "<br><div align='center'><b>".$LANG['plugin_additionalalerts']['alert'][10]."</b></div>";
            }
            echo "<br>";
         }
      }

      if ($additionalalerts_new_ocs!=0) {
         if ($CFG_GLPI["use_ocs_mode"]&&Session::haveRight("ocsng","w")) {

            foreach ($DB->request("glpi_ocsservers","`is_active` = 1") as $config) {

               $query=PluginAdditionalalertsOcsAlert::queryNew($config,$_SESSION["glpiactive_entity"]);
               $result = $DB->query($query);

               if ($DB->numrows($result)>0) {

                  if (Session::isMultiEntitiesMode()) {
                     $nbcol=9;
                  } else {
                     $nbcol=8;
                  }

                  echo "<div align='center'><table class='tab_cadre' cellspacing='2' cellpadding='3'><tr><th colspan='$nbcol'>";
                  echo $LANG['plugin_additionalalerts']['alert'][9]."</th></tr>";
                  echo "<tr><th>".$LANG['common'][16]."</th>";
                  if (Session::isMultiEntitiesMode())
                     echo "<th>".$LANG['entity'][0]."</th>";
                  echo "<th>".$LANG['computers'][9]."</th>";
                  echo "<th>".$LANG['state'][0]."</th>";
                  echo "<th>".$LANG['common'][15]."</th>";
                  echo "<th>".$LANG['common'][34]." / ".$LANG['common'][35]." / ".$LANG['common'][18]."</th>";
                  echo "<th>".$LANG['ocsng'][14]."</th>";
                  echo "<th>".$LANG['ocsng'][13]."</th>";
                  echo "<th>".$LANG['ocsng'][29]."</th></tr>";

                  while ($data=$DB->fetch_array($result)) {
                     echo PluginAdditionalalertsOcsAlert::displayBody($data);
                  }
                  echo "</table></div>";
               } else {
                  echo "<br><div align='center'><b>".$LANG['plugin_additionalalerts']['alert'][8]."</b></div>";
               }
            }
            echo "<br>";
         }
      }

      if ($additionalalerts_ocs!=0) {
         if ($CFG_GLPI["use_ocs_mode"]&&Session::haveRight("ocsng","w")) {
         
            foreach ($DB->request("glpi_ocsservers","`is_active` = 1") as $config) {
               $query=PluginAdditionalalertsOcsAlert::query($delay_ocs,$config,$_SESSION["glpiactive_entity"]);
               $result = $DB->query($query);
               if ($DB->numrows($result)>0) {

                  if (Session::isMultiEntitiesMode()) {
                     $nbcol=9;
                  } else {
                     $nbcol=8;
                  }
                  echo "<div align='center'><table class='tab_cadre' cellspacing='2' cellpadding='3'><tr><th colspan='$nbcol'>";
                  echo $LANG['plugin_additionalalerts']['alert'][1]." ".$delay_ocs." ".$LANG['plugin_additionalalerts']['setup'][15]."</th></tr>";
                  echo "<tr><th>".$LANG['common'][16]."</th>";
                  if (Session::isMultiEntitiesMode())
                     echo "<th>".$LANG['entity'][0]."</th>";
                  echo "<th>".$LANG['computers'][9]."</th>";
                  echo "<th>".$LANG['state'][0]."</th>";
                  echo "<th>".$LANG['common'][15]."</th>";
                  echo "<th>".$LANG['common'][34]." / ".$LANG['common'][35]." / ".$LANG['common'][18]."</th>";
                  echo "<th>".$LANG['ocsng'][14]."</th>";
                  echo "<th>".$LANG['ocsng'][13]."</th>";
                  echo "<th>".$LANG['ocsng'][29]."</th></tr>";

                  while ($data=$DB->fetch_array($result)) {

                     echo PluginAdditionalalertsOcsAlert::displayBody($data);
                  }
                  echo "</table></div>";
               } else {
                  echo "<br><div align='center'><b>".$LANG['plugin_additionalalerts']['alert'][3]." ".$delay_ocs." ".$LANG['plugin_additionalalerts']['setup'][15]."</b></div>";
               }
            }
            echo "<br>";
         }
      }
   }
}

?>