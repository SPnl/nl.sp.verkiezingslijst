<?php

class CRM_Verkiezingslijst_BAO extends CRM_Verkiezingslijst_DAO {
  
  public static function findByPartij($partij_contact_id) {
    $option_group_id = CRM_Core_BAO_OptionGroup::getFieldValue('CRM_Core_DAO_OptionGroup', 'verkiezingen', 'id', 'name');
    
    $sql = "SELECT `v`.*, `ov`.`label` AS `verkiezing` FROM `civicrm_verkiezingslijst` `v`
            INNER JOIN `civicrm_option_value`  `ov` ON `v`.`verkiezing` = `ov`.`value` AND `ov`.`option_group_id` = %1
            WHERE `partij_contact_id`  = %2 
            ORDER BY `ov`.`weight`, `v`.`positie`";
    
    $dao = CRM_Core_DAO::executeQuery($sql, array(
      1 => array($option_group_id, 'Integer'),
      2 => array($partij_contact_id, 'Integer')
    ), TRUE, 'CRM_Verkiezingslijst_DAO');
    
    $return = array();
    while($dao->fetch()) {
      $temp = array();
      CRM_Core_DAO::storeValues($dao, $temp);
      $return[] = $temp;
    }
    return $return;  
  }
  
}
