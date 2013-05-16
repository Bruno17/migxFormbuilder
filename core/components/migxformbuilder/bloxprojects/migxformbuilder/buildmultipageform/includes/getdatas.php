<?php

$modx->addPackage($this->bloxconfig['packagename'], $modx->getOption('core_path') . 'components/' . $this->bloxconfig['packagename'] . '/model/');

$formids = $modx->getOption('form_ids', $this->bloxconfig, '');
//$task = $modx->getOption('task', $this->bloxconfig, '');
$task = 'buildform';
$debug_formit = $modx->getOption('debug_formit', $this->bloxconfig, '');
$direction = isset($_REQUEST['next']) ? 'next' : '';
$direction = empty($direction) && isset($_REQUEST['back']) ? 'back' : $direction;

$dovalidate = $direction == 'back' ? false : true;
$placeholderPrefix = 'fi.';
$sessionVarKey = 'migxformrequest';
$_SESSION[$sessionVarKey]['form_ids'] = $formids;
$formids = explode(',', $formids);

$lastform_id = $modx->getOption('form_id', $_REQUEST, '');
$form_index = array_search($lastform_id, $formids);

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


//validation (lastform))
if ($object = $modx->getObject('mfbForm', $lastform_id)) {

    $fieldsets = $modx->fromJson($object->get('json'));
    $validate = '';
    $hooks = array();

    foreach ($fieldsets as $fieldset) {
        $fields = $modx->fromJson($fieldset['fields']);
        $validate = array();
        $validate[] = 'homephone:blank';
        foreach ($fields as $field) {
            $field['name'] = $field['name'] == 'Extended Field' ? 'extended_' . $field['extendedname'] : $field['name'];
            $field['name'] = str_replace(' ', '_', $field['name']);

            $_SESSION[$sessionVarKey][$field['name']] = $_POST[$field['name']];

            if (!empty($field['validate'])) {
                if (is_array($field['validate'])) {
                    $field['validate'] = implode(':', $field['validate']);
                }
                $validate[] = $field['name'] . ':' . $field['validate'];
            }
        }
        $validate = implode(',', $validate);
    }

    if (isset($_REQUEST['submit']) || !empty($direction)) {
        if (isset($_SESSION[$sessionVarKey])) {
            $_POST = array_merge($_SESSION[$sessionVarKey], $_POST);
        }
    }

    $params['hooks'] = '';
    if ($dovalidate && !empty($validate)) {
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

    $hooks[] = 'mfb_formit_validated';

    if ($direction != 'back' && !empty($sendmail)) {
        $params['emailTo'] = $modx->getOption('emailTo', $extended, '');
        $params['emailSubject'] = $modx->getOption('emailSubject', $extended, '');
        $params['emailTpl'] = 'migxFormReportTpl';
        $hooks[] = 'email';
    }

    if ($direction != 'back' && !empty($sendautoresponse)) {
        $params['replyTo'] = $modx->getOption('replyTo', $extended, '');
        $fiarFrom = $modx->getOption('fiarFrom', $extended, '');
        if (!empty($fiarFrom)) {
            $params['fiarFrom'] = $fiarFrom;
        }
        $fiarFromName = $modx->getOption('fiarFromName', $extended, '');
        if (!empty($fiarFromName)) {
            $params['fiarFromName'] = $fiarFromName;
        }
        $fiarSubject = $modx->getOption('fiarSubject', $extended, '');
        if (!empty($fiarSubject)) {
            $params['fiarSubject'] = $fiarSubject;
        }
        $hooks[] = 'FormItAutoResponder';
        $params['fiarTpl'] = 'migxFormAutoRespondTpl';

    }


    if ($direction != 'back' && !empty($redirectTo)) {
        $params['redirectTo'] = $redirectTo;
        $hooks[] = 'redirect';
    }

    $params['hooks'] = implode(',', $hooks);

    if (!isset($_REQUEST['submit'])){
        $params['clearFieldsOnSuccess'] = 0;
    }

    if (!empty($debug_formit)){
        echo '<h3>Formit Properties</h3>';
        echo '<pre>'.print_r($params,1).'</pre>';    
    }    

    $modx->runSnippet('FormIt', $params);

    $validated = $modx->getPlaceholder('mfb_formit_validated');

}

if (empty($validated)) {
    $formid = !empty($lastform_id) ? $lastform_id : $formids[0];
} else {
    switch ($direction) {
        case 'next':
            $form_index++;
            $formid = $formids[$form_index];
            break;
        case 'back':
            $form_index--;
            $formid = $formids[$form_index];
            break;
        default:
            $formid = $formids[0];
    }
}


if ((empty($direction) && $validated) || (empty($direction) && !isset($_REQUEST['submit']))) {
    unset($_SESSION[$sessionVarKey]);
}


/*
if (is_array($_SESSION['request'])){
foreach ($_SESSION['request'] as $field=>$value){
if ( is_array($value)){
$modx->setPlaceholder($placeholderPrefix . $field, $modx->toJson($value));    
}else{
$modx->setPlaceholder($placeholderPrefix . $field, $value);    
}
}
}
*/

//print_r($_SESSION[$sessionVarKey]);


if ($object = $modx->getObject('mfbForm', $formid)) {
    $bloxdatas = $object->toArray();
    $bloxdatas['_formidx'] = $form_index+1;
    $bloxdatas['_first'] = $form_index == 0 ? '1' : '0';
    $bloxdatas['_last'] = $form_index+1 == count($formids) ? '1' : '0';
    $bloxdatas['form_ids'] = $formids;
    $bloxdatas['_pages'] = count($formids);

    $fieldsets = $modx->fromJson($object->get('json'));
    $validate = '';
    $hooks = array();


    foreach ($fieldsets as $fieldset) {
        $fields = $modx->fromJson($fieldset['fields']);
        foreach ($fields as $field) {
            if (isset($field[$task . '_chunk']) && !empty($field[$task . '_chunk'])) {
                $field['tpl'] = $field[$task . '_chunk'];
            } else {
                $field['tpl'] = 'input_' . $field['type'];
            }
            $field['name'] = $field['name'] == 'Extended Field' ? 'extended_' . $field['extendedname'] : $field['name'];
            $field['name'] = str_replace(' ', '_', $field['name']);
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
            if (isset($field['getvaluefromrequest']) && !empty($field['getvaluefromrequest'])) {
                if (isset($_REQUEST[$field['name']])) {
                    $value = rawurlencode($_REQUEST[$field['name']]);
                } elseif (isset($_REQUEST[$field['extendedname']])) {
                    $value = rawurlencode($_REQUEST[$field['extendedname']]);
                }
                if (!empty($value)) {
                    $modx->setPlaceholder($placeholderPrefix . $field['name'], $value);
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
