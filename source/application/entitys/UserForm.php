<?php

class Application_Entity_UserForm extends Core_Form_Form
{

    public function init()
    {

        $obj = new Application_Model_DbTable_User();
        $primaryKey = $obj->getPrimaryKey();
        $this->setMethod('post');
        $this->setEnctype('multipart/form-data');
        $this->setAttrib('idfile', $primaryKey);
        $this->setAction('/default/index/insert');
        $e = new Zend_Form_Element_Hidden($primaryKey);
        $this->addElement($e);
        $e = new Zend_Form_Element_MultiCheckbox('hobby');
       // $e->addMultiOptions(array('futbol' => "futbol", 'billar' => 'billar', 'natacion' => 'natacion'));
      
          $e->addMultiOptions(array('1' => "futbol", '2' => 'billar', '3' => 'natacion'));
       
        $this->addElement($e);
        $e = new Zend_Form_Element_Text('name');
        $this->addElement($e);
        $e = new Zend_Form_Element_Text('lastName');
        $this->addElement($e);
        $e = new Zend_Form_Element_Text('firstName');
        $this->addElement($e);
        $e = new Zend_Form_Element_Text('mail');
        $this->addElement($e);
        $e = new Zend_Form_Element_Text('age');
        $this->addElement($e);
        $e = new Zend_Form_Element_Submit('enviar');
        $this->addElement($e);
        $e = new Zend_Form_Element_Checkbox('flagAct');
        $e->setValue(true);
        $this->addElement($e);
        foreach ($this->getElements() as $element)
        {
            $element->removeDecorator('Label');
            $element->removeDecorator('DtDdWrapper');
            $element->removeDecorator('HtmlTag');
        }
    }

    public function populate(array $values)
    {
        parent::populate($values);
    }

}

