<?php

class CRM_Verkiezingslijst_Form_Report_Verkiezingslijst extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupExtends = array();
  protected $_customGroupGroupBy = FALSE;
  protected $_add2groupSupported = TRUE;

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
          ),
          'gekozen' => array(
            'pseudofield' => true,
            'dbAlias' => 'v.gekozen',
            'title' => ts('Gekozen'),
            'type' => CRM_Utils_Type::T_INT,
            'operatorType' => CRM_Report_Form::OP_SELECT,
            'options' => array('' => ts(' - Select - '), 0 => ts('Nee'), 1 => ts('Ja')),
          ),
          'afdeling' => array(
            'pseudofield' => true,
            'dbAlias' => 'afdeling.id',
            'title' => ts('Afdeling'),
            'type' => CRM_Utils_Type::T_INT,
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $this->getAfdelingen(),
          ),
          'provincie' => array(
            'pseudofield' => true,
            'dbAlias' => 'civicrm_value_adresgegevens_12.provincie_28',
            'title' => ts('Provincie'),
            'type' => CRM_Utils_Type::T_STRING,
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $this->getProvincies(),
          ),
        )
      )
    );
    parent::__construct();
		$this->_aliases['civicrm_contact'] = 'kandidaat';
  }

  function preProcess() {
    $this->assign('reportTitle', ts('Kandidaten verkiezingslijsten'));
    parent::preProcess();
  }
	
	public function getAfdelingen() {
		$afdelingen = civicrm_api3('Contact', 'get', array(
			'contact_type' => 'Organization',
			'contact_sub_type' => 'SP_Afdeling',
			'is_deleted' => 0,
			'options' => array('limit' => 0),
			'return' => array('id', 'display_name'),
		));
		$return = array();
		foreach($afdelingen['values'] as $afdeling) {
			$return[$afdeling['id']] = $afdeling['display_name'];
		}
		return $return;
	}

	public function getProvincies() {
		return array(
			'Drenthe' => 'Drenthe',
			'Flevoland' => 'Flevoland',
			'Friesland' => 'Friesland',
			'Gelderland' => 'Gelderland',
			'Groningen' => 'Groningen',
			'Limburg' => 'Limburg',
			'Noord-Brabant' => 'Noord-Brabant',
			'Noord-Holland' => 'Noord-Holland',
			'Zeeland' => 'Zeeland',
			'Zuid-Holland' => 'Zuid-Holland'
		);
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
    $gekozen = CRM_Utils_Array::value('gekozen_value', $this->_params);
    if ($gekozen) {
    	$this->_whereClauses[] = $this->whereClause($this->_columns['civicrm_verkiezingslijst']['filters']['gekozen'],
        $op,
        CRM_Utils_Array::value("gekozen_value", $this->_params),
        CRM_Utils_Array::value("gekozen_min", $this->_params),
        CRM_Utils_Array::value("gekozen_max", $this->_params)
      );
    }
    $afdeling_op = CRM_Utils_Array::value("afdeling_op", $this->_params);
    $afdeling_value = CRM_Utils_Array::value("afdeling_value", $this->_params);
    if ($afdeling_op && $afdeling_value) {
      $this->_whereClauses[] = $this->whereClause($this->_columns['civicrm_verkiezingslijst']['filters']['afdeling'],
        CRM_Utils_Array::value("afdeling_op", $this->_params),
        CRM_Utils_Array::value("afdeling_value", $this->_params),
        CRM_Utils_Array::value("afdeling_min", $this->_params),
        CRM_Utils_Array::value("afdeling_max", $this->_params)
      );
    }
		$provincie_op = CRM_Utils_Array::value("provincie_op", $this->_params);
		$provincie_value = CRM_Utils_Array::value("provincie_value", $this->_params);
    if ($provincie_op && $provincie_value) {
      $this->_whereClauses[] = $this->whereClause($this->_columns['civicrm_verkiezingslijst']['filters']['provincie'],
        CRM_Utils_Array::value("provincie_op", $this->_params),
        CRM_Utils_Array::value("provincie_value", $this->_params),
        CRM_Utils_Array::value("provincie_min", $this->_params),
        CRM_Utils_Array::value("provincie_max", $this->_params)
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