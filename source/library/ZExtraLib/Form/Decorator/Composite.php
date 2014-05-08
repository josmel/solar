    <?php 
    class ZExtraLib_Form_Decorator_Composite extends Zend_Form_Decorator_Abstract
    {
        public function buildLabel()
        {
           
            $element = $this->getElement();
            $label = $element->getLabel();
            if ($translator = $element->getTranslator()) {
                $label = $translator->translate($label);
            }
           
            
            return $element->getView()
                           ->formLabel($element->getName(), $label);
        }
     
        public function buildInput()
        {
            $element = $this->getElement();
            $helper  = $element->helper;
            return $element->getView()->$helper(
                $element->getName(),
                $element->getValue(),
                $element->getAttribs(),
                $element->options
            );
        }
     
        public function buildErrors()
        {
            $element  = $this->getElement();
            $messages = $element->getMessages();
            if (empty($messages)) {
                return '';
            }
            return '<div class="errors">' .
                   $element->getView()->formErrors($messages) . '</div>';
        }
     
        public function buildDescription()
        {
            $element = $this->getElement();
            $desc    = $element->getDescription();
            if (empty($desc)) {
                return '';
            }
            return '<div class="description">' . $desc . '</div>';
        }
     
        public function render($content)
        {
            $element = $this->getElement();
            if (!$element instanceof Zend_Form_Element) {
                return $content;
            }
            if (null === $element->getView()) {
                return $content;
            }
            $output = "";
            $separator = $this->getSeparator();
            $placement = $this->getPlacement();
            $label     = $this->buildLabel();
            $input     = $this->buildInput();
            $errors    = $this->buildErrors();
            $desc      = $this->buildDescription();
            
            if ($element->isRequired()) {
                $label='<label for=""><span class="required"> * </span><strong>'.
                        $element->getLabel()
                        .'</strong></label>';
            }
            if ($element->getValidator('ZExtraLib_Validate_NumberWordsAllowed'))
            {
                
                $input=$input.
                '<span class="block">
                 <span class="inline">Te quedan <span id="display_count"></span> palabras</span><span class="inf"></span>
                 <span class="display_count_error error"></span>                                   
                 </span>';
            }
            
            if($element->getType() == 'Zend_Form_Element_MultiCheckbox'){
                $input="";
                $view = $element->getView();
                $values = $element->getValue();
                $name = $element->getName();
                
                foreach ($element->getMultiOptions() as $val => $label1) {
        	$checked = (is_array($values) && in_array($val,$values)) ? 'checked' : '';
                $input .= '<li>' . 
                        '<label for="'.$name.'-'.$val.'">'.
                        '<input id="'.$name.'-'.$val.'" type="checkbox" '.$checked.' value="'.$val.'" name="'.$name.'[]">'.
                        $label1.
                        '</label>'.
                         //       $view->formCheckbox($name.'-'.$val,$val,array('checked' => $checked)) .
                                //$view->formLabel($name,$label1) .
                          '</li>';
 
            }
                $input = '<ul>'.$input.'</ul>';
                
                $output =$input
                    . $errors
                    . $desc
                    ;
            }
            
            if($output ==""){
            $output =
                     '<dt>'.$label.'</dt>'
                    .'<dd>'.$input.'</dd>'
                    . $errors
                    . $desc
                    ;
            }
            switch ($placement) {
                case (self::PREPEND):
                    return $output . $separator . $content;
                case (self::APPEND):
                default:
                    return $content . $separator . $output;
            }
        }
    }