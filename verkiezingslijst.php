<?php

require_once 'verkiezingslijst.civix.php';

/**
 * Implementatio of hook__civicrm_tabs
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_tabs
 */
function verkiezingslijst_civicrm_tabs(&$tabs, $contactID) {
  $settings = CRM_Verkiezingslijst_Config_Settings::singleton();
  
  $isPartijContact = false;
  $subtypes = CRM_Contact_BAO_Contact::getContactSubType($contactID);
  foreach($subtypes as $subtype) {
    if (in_array(strtolower($subtype), $settings->getPartijContactTypes())) {
      $isPartijContact = true;
      break;
    }
  }
  
  if ($isPartijContact) {
    $url = CRM_Utils_System::url('civicrm/contact/verkiezingslijst', "&reset=1&cid=$contactID&snippet=1");
  
    $tabs[] = array(
      'id' => 'verkiezingslijst',
      'url' => $url,
      'count' => CRM_Verkiezingslijst_BAO::getCountPerPartij($contactID),
      'title' => ts('Verkiezingslijsten'),
      'weight' => -100
    );
  }
}

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function verkiezingslijst_civicrm_config(&$config) {
  _verkiezingslijst_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function verkiezingslijst_civicrm_xmlMenu(&$files) {
  _verkiezingslijst_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function verkiezingslijst_civicrm_install() {
  return _verkiezingslijst_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function verkiezingslijst_civicrm_uninstall() {
  return _verkiezingslijst_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function verkiezingslijst_civicrm_enable() {
  return _verkiezingslijst_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function verkiezingslijst_civicrm_disable() {
  return _verkiezingslijst_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function verkiezingslijst_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _verkiezingslijst_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function verkiezingslijst_civicrm_managed(&$entities) {
  return _verkiezingslijst_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function verkiezingslijst_civicrm_caseTypes(&$caseTypes) {
  _verkiezingslijst_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function verkiezingslijst_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _verkiezingslijst_civix_civicrm_alterSettingsFolders($metaDataFolders);
}
