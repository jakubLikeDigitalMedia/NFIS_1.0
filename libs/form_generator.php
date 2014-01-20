<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 09/01/14
 * Time: 21:58
 */

class FormGenerator{

    private $name;
    private $action;
    private $method;
    private $class = '';

    private $elements = array();
    private $groups = array();

    // default element classes
    private $labelClass = 'label';
    private $TextFieldClass = 'text-field';
    private $ButtonClass = 'button';

    private $ButtonValue = 'Submit';
    private $ButtonName = 'submit';
    private $ButtonOptions = array();

    //default form attributes
    private $textFieldSize = 40;

    private $blocks = array();




    public function __construct($name, $action, $method, $class = ''){
        $this->name = $name;
        $this->action = $action;
        $this->method = $method;
        $this->class = $class;
    }

    public function setTextFieldSize($size){
        $this->textFieldSize = $size;

    }


    public function createElement($type, $label, $name, $value, $options = array()){
        return $this->renderElement(array(
                                        'type' => $type,
                                        'label' => $label,
                                        'name' => $name,
                                        'value' => $value,
                                        'options' => $options
                                        )
                                    );

    }

    public function createElements($elements, $groupName = NULL){
        $newElements = array();
        foreach($elements as $element){
            $newElements[] = $this->createElement($element['type'], $element['label'], $element['name'], $element['value'], $element['options']);
        }
        return (!empty($groupName))? array($groupName => $newElements): $newElements;
    }

    public function addHTMLBlock($block){
        $this->blocks[] = $block;
    }

    public function setSubmitButton($name, $value, $options){
        $this->ButtonName = $name;
        $this->ButtonValue = $value;
        $this->ButtonOptions = $options;
    }

    private function renderElement($element){
        $error = $this->getOption($element, 'error');
        $class = $this->getOption($element, 'class');
        $wrapper = $this->getOption($element, 'wrapper');

        $multiple = $this->getOption($element, 'multiple');
        $multiple = (!empty($multiple))? '[]': '';

        $disabled = $this->getOption($element, 'disabled');
        $disabled = (!empty($disabled))? "disabled=\"disabled\"": '';

        $size = $this->getOption($element, 'size');
        $size = (empty($size))? $this->textFieldSize: $size;

        $id = "{$this->name}_{$element['name']}";
        $HTML = "<label for=\"$id\" class=\"{$this->labelClass}\">{$element['label']}</label>";
        $HTML .= (!empty($error))? "<div class=\"error\">$error</div>": '';
        switch($element['type']){
            case 'text':
                $el = "<input type=\"text\" id=\"$id\" class=\"$class\" name=\"{$element['name']}$multiple\" value=\"{$element['value']}\" size=\"$size\" $disabled>";
                $HTML .= (!empty($wrapper))? "<$wrapper>$el</$wrapper>": $el;
                return $HTML;
                break;
            case 'select':
                $HTML .= "<select id=\"$id\" class=\"$class\" name=\"{$element['name']}$multiple\">";
                $options = $element['value']['values'];
                if (is_array($options)){
                    $initVal = (empty($options))? 'No options are available': 'Select value';
                    $selectOptions = '<option value="0">'.$initVal.'</option>';
                    $selected = $element['value']['selected'];
                    foreach($options as $key => $option){
                        $selectOptions .= (!empty($selected) && $selected == $key)? '<option value="'.$key.'" selected="selected">'.$option.'</option>': '<option value="'.$key.'">'.$option.'</option>';
                    }
                    return $HTML .= $selectOptions.'</select>';
                }
                else return $HTML.'</select>';
                break;
            case 'checkbox':
                 $checked = $this->getOption($element, 'checked');
                 $checked = (!empty($disabled))? "checked=\"checked\"": '';
                 $el = "<input type=\"checkbox\" id=\"$id\" class=\"$class\" name=\"{$element['name']}{$multiple}\" value=\"{$element['value']}\" $checked $disabled>";
                 $HTML .= (!empty($wrapper))? "<$wrapper>$el</$wrapper>": $el;
                 return $HTML;
                 break;
        }
    }


    private function getOption($element, $option){
        switch($option){
            case 'class':
                return (isset($element['options']['class']))? $this->TextFieldClass.' '.$element['options']['class']: $this->TextFieldClass;
                break;
            default:
                return (isset($element['options'][$option]))? $element['options'][$option]:'';
        }
    }

    public function setElementOption(&$element, $option, $value){
        $element['options'][$option] = $value;
    }

    public function setOptionToElements(&$elements, $option, $value){
        foreach($elements as $key => $element){
            $this->setElementOption($elements[$key], $option, $value);
        }
    }
    
    /*
     * Adding elements to form
     * -----------------------
     * function create sub array containing grouped elements and add this to elements array
     * @elements array of created elements
     * @groupName name of group
     */
    public function addElementsToGroup($groupName, $elements, $parentGroup = NULL){
        if (!empty($parentGroup)){
            $this->addElementsToParentGroup($this->elements, $parentGroup, $groupName, $elements);
            return TRUE;
        }
        else{
            (is_array($elements))? $this->elements[$groupName] = $elements: $this->elements[$groupName][] = $elements;
            return TRUE;
        }
    }

    private function addElementsToParentGroup(&$groups, $parentGroupName, $childGroupName, $elements){
        foreach ($groups as $groupName => $groupElements) {
            //$this->addElement($groups[$groupName]);
            if ($parentGroupName === $groupName){
                (is_array($elements))? $groups[$parentGroupName][$childGroupName] = $elements: $groups[$parentGroupName][$childGroupName][] = $elements;
            }
            elseif (is_array($groupElements)) $this->addElementsToParentGroup($groupElements, $parentGroupName, $childGroupName, $elements);

        }
        return TRUE;
    }

    public function addElements($elements){
        foreach ($elements as $group => $groupElements) {
            if (is_string($group))$this->createElementsInGroup($group, $groupElements);
            else {
                $element = $groupElements; // there is no group, so assume that $groupElements is single element
                $this->addElement($this->createElement($element['type'], $element['label'], $element['name'], $element['value'], $element['options']));
            }
        }
    }


    /*
      * function creates array of created element with @group param as key of array
      * function is able to create sub arrays if list of @elements param contains name of group
      * params:
      * @group: name of group (fieldset)
      * @elements: array of elements
      */
    private function createElementsInGroup($group, $elements, $parentGroup = NULL){
        foreach ($elements as $key => $element) {
            if (is_string($key) AND is_array($element)) $this->createElementsInGroup($key, $element, $group);
            else $this->addElementsToGroup($group, $this->createElement($element['type'], $element['label'], $element['name'], $element['value'], $element['options']), $parentGroup);
        }
        return TRUE;

    }

    public function addElement($element){
        $this->elements[] = $element;
    }
    //======================================================================================================================================


    public function startFormTag(){

        $class = (!empty($this->class))? "class=\"{$this->class}\"": '';
        if ( ($this->method == 'put') || ($this->method == 'delete')){
            $form = "<form id=\"{$this->name}\" $class action=\"$this->action\" method=\"post\">";
            $form .= "<input type=\"hidden\" name=\"_method\" value=\"{$this->method}\">";
        }
        else $form = "<form id=\"{$this->name}\" $class action=\"$this->action\" method=\"{$this->method}\">";
        return $form;
    }

    public function endFormTag(){
        return '</form>';
    }

    public function render($type = NULL){
        $form = $this->startFormTag();
        $form .= $this->renderElements($this->elements, $type);
        if (!empty($this->blocks)){
            foreach($this->blocks as $block){
                $form .= $block;
            }
        }
        $form .= $this->createSubmitButton();
        $form .= '</form>';
        return $form;

    }

    public function renderElements($elements, $type = NULL){
        $HTML = '';
        foreach ($elements as $group => $elements) {
            $elements = (is_string($elements))? array($elements): $elements;
            $HTML .= $this->renderGroup($group, $elements, $type);
        }
        return $HTML;
    }

    private function renderGroup($label, $elements, $type){
        $HTML = (empty($label) OR is_numeric($label))? '': "<fieldset><legend>$label</legend>";
        switch($type){
            case 'list':
                $HTML .= '<ul>';
                foreach ($elements as $key => $value) {
                    if (is_string($key)) $this->renderGroup($key, $value, $type);
                    $HTML .= (is_array($value))? "<li>{$this->renderGroup($key, $value, $type)}</li>": "<li>$value</li>";
                }
                $HTML .= '</ul>';
                //return $HTML;
                break;
            case 'div':
                foreach ($elements as $key => $value) {
                    if (is_string($key)) $this->renderGroup($key, $value, $type);
                    $HTML .= (is_array($value))? "<div>{$this->renderGroup($key, $value, $type)}</div>": "<div>$value</div>";
                }
                //return $HTML;
                break;
            default:
                foreach ($elements as $key => $value) {
                    if (is_string($key)) $this->renderGroup($key, $value, $type);
                    $HTML .= (is_array($value))? "{$this->renderGroup($key, $value, $type)}": "$value";
                }
                //return $HTML;
        }

        //$HTML .= $this->renderElements($elements, $type);
        $HTML .= (empty($label) OR is_numeric($label))? '': '</fieldset>';
        return $HTML;

    }

    public function createSubmitButton(){
        $class = (isset($options['class']))? "class=\"{$this->ButtonClass} {$options['class']}\"": $this->ButtonClass;
        $options = $this->ButtonOptions;
        $HTML = "<button id=\"{$this->ButtonName}\" type=\"submit\" $class >{$this->ButtonValue}</button>";
        return (isset($options['wrapper']))? "<{$options['wrapper']}>$HTML</{$options['wrapper']}>": $HTML;

    }
}
