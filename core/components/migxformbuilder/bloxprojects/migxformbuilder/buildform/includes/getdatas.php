<?php

$formid = $modx->getOption('form_id', $this->bloxconfig, '');

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

    $modx->runSnippet('FormIt', $params);

    foreach ($fieldsets as $fieldset) {
        $fields = $modx->fromJson($fieldset['fields']);
        foreach ($fields as $field) {
            $field['tpl'] = 'input_' . $field['type'];
            $field['name'] = $field['name'] == 'Extended Field' ? 'extended_' . $field['extendedname'] : $field['name'];
            $field['name'] = str_replace(' ','_',$field['name']);
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
