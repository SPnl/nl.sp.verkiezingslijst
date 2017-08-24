<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Verkiezingslijst_Form_Verkiezingslijst extends CRM_Core_Form {
  
  protected $_contactId;
  
  protected $_id;
  
  function preProcess() {
    parent::preProcess();
    $this->_contactId = CRM_Utils_Request::retrieve('cid', 'Positive', $this);
    $this->_id = CRM_Utils_Request::retrieve('id', 'Positive', $this);

    $userContext = CRM_Utils_System::url('civicrm/contact/view', 'reset=1&&selectedChild=verkiezingslijst&cid='.$this->_contactId);
    $session = CRM_Core_Session::singleton();
    $session->replaceUserContext($userContext);
  }
  
  function buildQuickForm() {
    $this->add('hidden', 'cid');
    $this->add('hidden', 'id');
    
    if ($this->_action & CRM_Core_Action::DELETE) {
      $this->addButtons(array(
        array(
          'type' => 'next',
          'name' => ts('Delete'),
          'isDefault' => TRUE,
        ),
        array(
          'type' => 'cancel',
          'name' => ts('Cancel'),
        ),
          )
      );
      return;
    } else {
      // add form elements
      $this->add(
        'select', // field type
        'verkiezing', // field name
        'Verkiezing', // field label
        $this->getVerkiezingen(), // list of options
        true // is required
      );
      $this->add(
        'text', // field type
        'positie', // field name
        'Positie', // field label
        true // is required
      );

      //CRM_Contact_Form_NewContact::buildQuickForm($this);
      $attributes = array(
        'multiple' => false,
        'create' => false,
        'api' => array('params' => array('is_deceased' => 0, 'contact_type' => 'Individual'))
      );
      $this->addEntityRef('kandidaat_ids', ts('Kandidaat'), $attributes, false);

      $this->add(
        'select', // field type
        'afdracht_verklaring_ondertekend', // field name
        'Afdracht-verklaring ondertekend', // field label
        array(0 => ts('Nee'), 1 => ts('Ja')), // list of options
        true // is required
      );

      $this->add(
        'select', // field type
        'verkozen', // field name
        'Verkozen', // field label
        array(0 => ts('Nee'), 1 => ts('Ja')), // list of options
        true // is required
      );

      $this->addButtons(array(
        array(
          'type' => 'done',
          'name' => ts('Submit'),
          'isDefault' => TRUE,
        ),
      ));
    }
    
    parent::buildQuickForm();
  }

  function postProcess() {
    $params = $this->controller->exportValues($this->_name);
    if ($this->_action & CRM_Core_Action::DELETE) {
      $dao = new CRM_Verkiezingslijst_BAO();
      $dao->id = $this->_id;
      if ($dao->find(TRUE)) {
        $dao->delete();
      }
      CRM_Core_Session::setStatus(ts('Selected kandidaat verwijderd.'), ts('Deleted'), 'success');
      return;
    }

    $kandidaat_ids = explode(',', $params["kandidaat_ids"]);
    $kandidaat_id = reset($kandidaat_ids);

    $bao = new CRM_Verkiezingslijst_BAO();
    $bao->positie = $params['positie'];
    $bao->verkiezing = $params['verkiezing'];
    $bao->kandidaat_contact_id = $kandidaat_id;
    $bao->partij_contact_id = $this->_contactId;
    
    if ($this->_id) {
      $bao->id = $this->_id;
    }
    
    $bao->save();
    
    parent::postProcess();
  }
  
  function getVerkiezingen() {
     return array(ts(' - Select -')) + CRM_Core_OptionGroup::values('verkiezingen');
  }
  
  function setDefaultValues() {
    $defaults = array();
    
    if ($this->_id) {
      $verkiezing = new CRM_Verkiezingslijst_BAO();
      $verkiezing->id = $this->_id;
      if ($verkiezing->find(TRUE)) {
        $this->_contactId = $verkiezing->partij_contact_id;
        $defaults['id'] = $verkiezing->id;
        $defaults['verkiezing'] = $verkiezing->verkiezing;
        $defaults['positie'] = $verkiezing->positie;
        $defaults['kandidaat_ids'] = array($verkiezing->kandidaat_contact_id);
        //$defaults['contact[1]'] = CRM_Contact_BAO_Contact::displayName($verkiezing->kandidaat_contact_id);
      }
    }
    
    
    $defaults['cid'] = $this->_contactId;
    return $defaults;
  }
  
  function validate() {
    if ($this->_action & CRM_Core_Action::DELETE) {
      return parent::validate();
    } else {
      $params = $this->controller->exportValues($this->_name);

      if (empty($params['kandidaat_ids'])) {
        $this->_errors['kandidaat_ids'] = ts('U moet een kandidaat selecteren');
      }

      if (empty($params['verkiezing'])) {
        $this->_errors['verkiezing'] = ts('U moet een verkiezing selecteren');
      }

      if (!empty($params['verkiezing']) && !empty($params['contact_select_id'][1])){
        //check if positie is not taken by another contact
        $check = CRM_Verkiezingslijst_BAO::checkKandidaat($this->_contactId, $params['verkiezing'], $params['contact_select_id'][1]);
        if ($check && (!$this->_id || $this->_id != $check)) {
          $display_name = CRM_Contact_BAO_Contact::displayName($params['contact_select_id'][1]);
          $this->_errors['contact[1]'] = ts('%1 is kandidaat voor deze verkiezingen', array(
            1 => $display_name,
          ));
        }
      }

      if (empty($params['positie'])) {
        $this->_errors['positie'] = ts('Positie is een verplicht veld');
      } 

      if (!empty($params['positie']) && !empty($params['verkiezing']) && !empty($params['contact_select_id'][1])){
        //check if positie is not taken by another contact
        $check = CRM_Verkiezingslijst_BAO::checkPositie($this->_contactId, $params['verkiezing'], $params['positie'], $params['contact_select_id'][1]);
        if ($check) {
          $display_name = CRM_Contact_BAO_Contact::displayName($check);
          $this->_errors['positie'] = ts('Plek %2 is al ingenomen door %1', array(
            1 => $display_name,
            2 => $params['positie'],
          ));
        }
      }
      return parent::validate();
    }
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
}
