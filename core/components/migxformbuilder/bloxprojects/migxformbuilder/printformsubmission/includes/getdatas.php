<?php

//$formid = $modx->getOption('form_id', $this->bloxconfig, '');
$object_id = $modx->getOption('object_id', $_GET, '');
$task = $modx->getOption('task', $this->bloxconfig, '');

$modx->addPackage($this->bloxconfig['packagename'], $modx->getOption('core_path') . 'components/' . $this->bloxconfig['packagename'] . '/model/');


//formbuilder
if ($object = $modx->getObject('mfbFormRequest', $object_id)) {

    $values = $object->toArray();
    $extended = $values['extended'];
    if (is_array($extended)) {
        foreach ($extended as $field => $value) {
            $values['extended_' . $field] = $value;
        }
    }

    $form_ids = $object->get('form_ids');
    if (!empty($form_ids)) {
        $form_ids = explode(',', $form_ids);
    } else {
        $form_ids = array();
        $form_ids[] = $object->get('form_id');
    }

    $forms = array();

    foreach ($form_ids as $form_id) {
        $form = array();
        //get object

        if ($object = $modx->getObject('mfbForm', $form_id)) {
            $form = $object->toArray();
            
            $hide = isset($form['extended']['hide_from_previews']) && !empty($form['extended']['hide_from_previews']) ? true : false;
            
            if ($hide){
                continue;
            }
            
            $fieldsets = $modx->fromJson($object->get('json'));
            $validate = '';
            $hooks = array();

            foreach ($fieldsets as $fieldset) {
                $fields = $modx->fromJson($fieldset['fields']);
                foreach ($fields as $field) {
                    if (isset($field[$task . '_chunk']) && !empty($field[$task . '_chunk'])) {
                        $field['tpl'] = $field[$task . '_chunk'];
                    }

                    $field['name'] = $field['name'] == 'Extended Field' ? 'extended_' . $field['extendedname'] : $field['name'];
                    $field['name'] = str_replace(' ', '_', $field['name']);
                    $field['value'] = $values[$field['name']];

                    switch ($field['type']) {
                        case 'radio':

                            break;
                        case 'checkbox':
                            $field['value'] = is_array($field['value']) ? implode(',', $field['value']) : $field['value'];
                            break;
                    }

                    $fieldset['innerrows']['field'][] = $field;
                }
                unset($fieldset['fields']);
                $form['innerrows']['fieldset'][] = $fieldset;
            }

        }

        $bloxdatas['innerrows']['form'][] = $form;

    }

}

//$bloxdatas['bloxoutput'] = print_r($fieldset,1);
//echo '<pre>' . print_r($bloxdatas, 1) . '</pre>';
