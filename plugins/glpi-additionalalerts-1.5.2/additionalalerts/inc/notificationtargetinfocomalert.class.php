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

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}

// Class NotificationTarget
class PluginAdditionalalertsNotificationTargetInfocomAlert extends NotificationTarget {

   function getEvents() {
      global $LANG;
      return array ('notinfocom' => $LANG['plugin_additionalalerts']['alert'][11]);
   }

   function getDatasForTemplate($event,$options=array()) {
      global $LANG, $CFG_GLPI;

      $this->datas['##notinfocom.entity##'] =
                        Dropdown::getDropdownName('glpi_entities',
                                                  $options['entities_id']);
      $this->datas['##lang.notinfocom.entity##'] =$LANG['entity'][0];
      
      $events = $this->getAllEvents();

      $this->datas['##lang.notinfocom.title##'] = $events[$event];
      
      $this->datas['##lang.notinfocom.name##'] = $LANG['common'][16];
      $this->datas['##lang.notinfocom.urlname##'] = "URL";
      $this->datas['##lang.notinfocom.computertype##'] = $LANG['common'][17];
      $this->datas['##lang.notinfocom.operatingsystem##'] = $LANG['computers'][9];
      $this->datas['##lang.notinfocom.state##'] = $LANG['state'][0];
      $this->datas['##lang.notinfocom.location##'] = $LANG['common'][15];
      $this->datas['##lang.notinfocom.urluser##'] = "URL";
      $this->datas['##lang.notinfocom.urlgroup##'] = "URL";
      $this->datas['##lang.notinfocom.user##'] = $LANG['common'][34];
      $this->datas['##lang.notinfocom.group##'] = $LANG['common'][35];
      
      foreach($options['notinfocoms'] as $id => $notinfocom) {
         $tmp = array();
         
         $tmp['##notinfocom.urlname##'] = urldecode($CFG_GLPI["url_base"]."/index.php?redirect=computer_".
                                    $notinfocom['id']);
         $tmp['##notinfocom.name##'] = $notinfocom['name'];
         $tmp['##notinfocom.computertype##'] = Dropdown::getDropdownName("glpi_computertypes",$notinfocom['computertypes_id']);
         $tmp['##notinfocom.operatingsystem##'] = Dropdown::getDropdownName("glpi_operatingsystems",$notinfocom['operatingsystems_id']);
         $tmp['##notinfocom.state##'] = Dropdown::getDropdownName("glpi_states",$notinfocom['states_id']);
         $tmp['##notinfocom.location##'] = Dropdown::getDropdownName("glpi_locations",$notinfocom['locations_id']);
         
         $tmp['##notinfocom.urluser##'] = urldecode($CFG_GLPI["url_base"]."/index.php?redirect=user_".
                                    $notinfocom['users_id']);
         
         $tmp['##notinfocom.urlgroup##'] = urldecode($CFG_GLPI["url_base"]."/index.php?redirect=group_".
                                    $notinfocom['groups_id']);

         $tmp['##notinfocom.user##'] = getUserName($notinfocom['users_id']);
         $tmp['##notinfocom.group##'] = Dropdown::getDropdownName("glpi_groups",$notinfocom['groups_id']);
         $tmp['##notinfocom.contact##'] = $notinfocom['contact'];
         
         $this->datas['notinfocoms'][] = $tmp;
      }
   }
   
   function getTags() {
      global $LANG;

      $tags = array('notinfocom.name'            => $LANG['common'][16],
                     'notinfocom.urlname'            => 'URL '.$LANG['common'][16],
                   'notinfocom.computertype'            => $LANG['common'][17],
                   'notinfocom.operatingsystem'    => $LANG['computers'][9],
                   'notinfocom.state' => $LANG['state'][0],
                   'notinfocom.location' => $LANG['common'][15],
                   'notinfocom.user'    => $LANG['common'][34],
                   'notinfocom.urluser' => 'URL '.$LANG['common'][34],
                   'notinfocom.group' => $LANG['common'][35],
                   'notinfocom.urlgroup' => 'URL '.$LANG['common'][35],
                   'notinfocom.contact' => $LANG['common'][18]);
      foreach ($tags as $tag => $label) {
         $this->addTagToList(array('tag'=>$tag,'label'=>$label,
                                   'value'=>true));
      }
      
      $this->addTagToList(array('tag'=>'additionalalerts',
                                'label'=>$LANG['plugin_additionalalerts']['alert'][11],
                                'value'=>false,
                                'foreach'=>true,
                                'events'=>array('notinfocom')));
      
      
      asort($this->tag_descriptions);
   }
}

?>