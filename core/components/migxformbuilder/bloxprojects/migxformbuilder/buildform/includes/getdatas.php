<?php

$formid = $modx->getOption('form_id', $this->bloxconfig, '');
$task = $modx->getOption('task', $this->bloxconfig, '');
$debug_formit = $modx->getOption('debug_formit', $this->bloxconfig, '');

$modx->addPackage($this->bloxconfig['packagename'], $modx->getOption('core_path') . 'components/' . $this->bloxconfig['packagename'] . '/model/');

/*
[[!FormIt?
&hooks=`spam,museumextrahooks,email,FormItAutoResponder`
&museumextrahooks=`toDb`
&validate=`phone:blank,museumid:required,name:required,email:required:email`
&emailTo=`b.perner@gmx.de`
&emailSubject=`Museumextra - Checklist`
&fiarSubject=`Museumextra - Checklist`  
&emailTpl=`tpl_checklist_email`
&fiarTpl=`tpl_checklist_email_autorespond`
&fiarFrom=`info@museumextra.nl` 
&classname=`meAccessibilityChecklist` 
]]
*/


//formbuilder
if ($object = $modx->getObject('mfbForm', $formid)) {
    $bloxdatas = $object->toArray();
    $fieldsets = $modx->fromJson($object->get('json'));
    $validate = '';
    $hooks = array();
    $placeholderPrefix = 'fi.'; 

    foreach ($fieldsets as $fieldset) {
        $fields = $modx->fromJson($fieldset['fields']);
        $validate = array();
        $validate[] = 'homephone:blank';
        foreach ($fields as $field) {
            $field['name'] = $field['name'] == 'Extended Field' ? 'extended_' . $field['extendedname'] : $field['name'];
            $field['name'] = str_replace(' ','_',$field['name']);            
            if (!empty($field['validate'])) {
                if (is_array($field['validate'])) {
                    $field['validate'] = implode(':', $field['validate']);
                }
                $validate[] = $field['name'] . ':' . $field['validate'];
            }
        }
        $validate = implode(',', $validate);
    }

    $params['hooks'] = '';
    if (!empty($validate)) {
        $params['validate'] = $validate;
    }

    $extended = $object->get('extended');
    $sendmail = $object->get('sendmail');
    $todb = $object->get('todb');
    $sendautoresponse = $object->get('sendautoresponse');
    $redirectTo = $modx->getOption('redirectTo', $extended, '');

    if (!empty($todb)) {
        $params['packagename'] = 'migxformbuilder';
        $params['classname'] = 'mfbFormRequest';
        $hooks[] = 'mfb_form2db';
    }

    if (!empty($sendmail)) {
        $params['emailTo'] = $modx->getOption('emailTo', $extended, '');
        $params['emailSubject'] = $modx->getOption('emailSubject', $extended, '');
        $params['emailTpl'] = 'migxFormReportTpl';
        $hooks[] = 'email';
    }
    
    if (!empty($sendautoresponse)) {
        $params['replyTo'] = $modx->getOption('replyTo', $extended, '');
        $fiarFrom = $modx->getOption('fiarFrom', $extended, '');
        if (!empty($fiarFrom)){
            $params['fiarFrom'] = $fiarFrom;     
        }
        $fiarFromName = $modx->getOption('fiarFromName', $extended, '');
        if (!empty($fiarFromName)){
            $params['fiarFromName'] = $fiarFromName;     
        }        
        $fiarSubject = $modx->getOption('fiarSubject', $extended, '');
        if (!empty($fiarSubject)){
            $params['fiarSubject'] = $fiarSubject;     
        }        
        $hooks[] = 'FormItAutoResponder';
        $params['fiarTpl'] = 'migxFormAutoRespondTpl';

    }    

    if (!empty($redirectTo)){
        $params['redirectTo'] = $redirectTo;
        $hooks[] = 'redirect';    
    }

    $params['hooks'] = implode(',', $hooks);
    
    foreach ($this->bloxconfig as $cfg=>$cfg_value){
        //overwrite formit-properties with scriptproperties,  starting with 'formit_'
        if (substr($cfg,0,7) == 'formit_'){
            $params[substr($cfg,7)] = $cfg_value;        
        }
    }
    
    if (!empty($debug_formit)){
        echo '<h3>Formit Properties</h3>';
        echo '<pre>'.print_r($params,1).'</pre>';    
    }

    $modx->runSnippet('FormIt', $params);

    foreach ($fieldsets as $fieldset) {
        $fields = $modx->fromJson($fieldset['fields']);
        foreach ($fields as $field) {
            if (isset($field[$task.'_chunk']) && !empty($field[$task.'_chunk'])){
                $field['tpl'] = $field[$task.'_chunk'];                    
            }
            else{
                $field['tpl'] = 'input_' . $field['type'];
            }            
            $field['name'] = $field['name'] == 'Extended Field' ? 'extended_' . $field['extendedname'] : $field['name'];
            $field['name'] = str_replace(' ','_',$field['name']);
            $field['required'] = '0';
            if (!empty($field['validate'])) {
                if (is_array($field['validate'])) {
                    $field['required'] = in_array('required',$field['validate']) ? '1' : '0';
                }
                else{
                    $field['required'] = $field['validate'] == 'required' ? '1' : '0';
                }
            }                
            $value = '';
            if (isset($field['getvaluefromrequest']) && !empty($field['getvaluefromrequest'])){
                if (isset($_REQUEST[$field['name']])){
                    $value = rawurlencode($_REQUEST[$field['name']]);
                }elseif(isset($_REQUEST[$field['extendedname']])){
                    $value = rawurlencode($_REQUEST[$field['extendedname']]);
                } 
                if (!empty($value)){
                    $modx->setPlaceholder($placeholderPrefix.$field['name'],$value);
                }
                
            }
            
            $opts = array();
            $options = $modx->fromJson($field['inputoptions']);
            if (is_array($options)) {
                foreach ($options as $option) {
                    $option['name'] = $field['name'];
                    $option['label'] = !empty($option['label']) ? $option['label'] : $option['value'];
                    $opts[] = $option;
                }                
            } else {
                $options = explode('||', $field['inputoptions']);
                foreach ($options as $option) {
                    $optparts = explode('==', $option);
                    $opt['name'] = $field['name'];
                    $opt['label'] = $optparts[0];
                    $opt['value'] = isset($optparts[1]) ? $optparts[1] : $opt['label'];
                    $opts[] = $opt;
                }
            }
            switch ($field['type']) {
                case 'radio':
                    $field['innerrows']['input_radio_option'] = $opts;
                    break;
                case 'checkbox':
                    $field['innerrows']['input_checkbox_option'] = $opts;
                    break;
            }

            $fieldset['innerrows']['field'][] = $field;
        }
        unset($fieldset['fields']);
        $bloxdatas['innerrows']['fieldset'][] = $fieldset;
    }


}

//echo '<pre>'.print_r($bloxdatas,1).'</pre>';
