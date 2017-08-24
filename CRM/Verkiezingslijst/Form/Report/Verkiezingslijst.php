<?php

class CRM_Verkiezingslijst_Form_Report_Verkiezingslijst extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupExtends = array();
  protected $_customGroupGroupBy = FALSE;
  protected $_add2groupSupported = FALSE;

  protected $_noFields = TRUE;

  function __construct() {
    $this->_groupFilter = FALSE;
    $this->_tagFilter = FALSE;
    $this->_columns = array(
      'civicrm_verkiezingslijst' => array(
        'filters' => array(
          'verkiezing' => array(
            'pseudofield' => true,
            'dbAlias' => 'v.verkiezing',
            'title' => ts('Verkiezing'),
            'type' => CRM_Utils_Type::T_STRING,
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Core_OptionGroup::values('verkiezingen'),
          )
        )
      )
    );
    parent::__construct();
  }

  function preProcess() {
    $this->assign('reportTitle', ts('Kandidaten verkiezingslijsten'));
    parent::preProcess();
  }

  public function select() {
    $this->_select = "SELECT 
        `v`.*, 
        `ov`.`label` AS `verkiezing`, 
        afdeling.display_name as afdeling, 
        kandidaat.display_name as kandidaat,
        civicrm_value_adresgegevens_12.gemeente_24 as gemeente,
        civicrm_value_adresgegevens_12.provincie_28 as provincie";
  }

  function from() {
    $option_group_id = CRM_Core_BAO_OptionGroup::getFieldValue('CRM_Core_DAO_OptionGroup', 'verkiezingen', 'id', 'name');
    $this->_from = "
            FROM `civicrm_verkiezingslijst` `v`
            INNER JOIN civicrm_contact afdeling ON v.partij_contact_id = afdeling.id
            INNER JOIN `civicrm_option_value`  `ov` ON `v`.`verkiezing` = `ov`.`value` AND `ov`.`option_group_id` = '".$option_group_id."'
            INNER JOIN civicrm_contact kandidaat ON v.kandidaat_contact_id = kandidaat.id
            LEFT JOIN civicrm_address ON civicrm_address.contact_id = kandidaat.id AND civicrm_address.is_primary = 1
            LEFT JOIN civicrm_value_adresgegevens_12 ON civicrm_value_adresgegevens_12.entity_id = civicrm_address.id";
  }

  public function storeWhereHavingClauseArray() {
    $op = CRM_Utils_Array::value("verkiezing_op", $this->_params);
    if ($op) {
      $this->_whereClauses[] = $this->whereClause($this->_columns['civicrm_verkiezingslijst']['filters']['verkiezing'],
        $op,
        CRM_Utils_Array::value("verkiezing_value", $this->_params),
        CRM_Utils_Array::value("verkiezing_min", $this->_params),
        CRM_Utils_Array::value("verkiezing_max", $this->_params)
      );
    }
  }

  public function orderBy() {
    $this->_orderBy = "ORDER BY `ov`.`weight`, afdeling.display_name, `v`.`positie`";
  }

  function modifyColumnHeaders() {
    // use this method to modify $this->_columnHeaders
    $this->_columnHeaders['verkiezing'] = array('title' => 'Verkiezing');
    $this->_columnHeaders['afdeling'] = array('title' => 'Afdeling');
    $this->_columnHeaders['positie'] = array('title' =>'Positie');
    $this->_columnHeaders['kandidaat_contact_id'] = array('title' =>'Kandidaat Contact ID');
    $this->_columnHeaders['kandidaat'] = array('title' =>'Kandidaat');
    $this->_columnHeaders['provincie'] = array('title' =>'Provincie');
    $this->_columnHeaders['gemeente'] = array('title' =>'Gemeente');
    $this->_columnHeaders['afdracht_verklaring_ondertekend'] = array('title' => "Afdracht verklaring ondertekend");
    $this->_columnHeaders['gekozen'] = array('title' => 'Gekozen');
  }

  public function alterDisplay(&$rows) {
    for($i=0; $i<count($rows); $i++) {
      $rows[$i]['afdracht_verklaring_ondertekend'] = $rows[$i]['afdracht_verklaring_ondertekend'] ? ts('Ja') : ts('Nee');
      $rows[$i]['gekozen'] = $rows[$i]['gekozen'] ? ts('Ja') : ts('Nee');
    }
  }

}