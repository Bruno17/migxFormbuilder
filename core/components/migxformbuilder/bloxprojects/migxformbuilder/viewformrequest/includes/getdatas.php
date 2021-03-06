<?php

$formid = $modx->getOption('form_id', $this->bloxconfig, '');
$object_id = $modx->getOption('object_id', $this->bloxconfig, '');
$task = $modx->getOption('task', $this->bloxconfig, '');

$modx->addPackage($this->bloxconfig['packagename'], $modx->getOption('core_path') . 'components/' . $this->bloxconfig['packagename'] . '/model/');

//get object
if ($object = $modx->getObject('mfbFormRequest', $object_id)) {
    $request = $object->toArray();
    $extended = $request['extended'];
    if (is_array($extended)) {
        foreach ($extended as $field => $value) {
            $request['extended_' . $field] = $value;
        }
    }
}

//formbuilder
if ($object = $modx->getObject('mfbForm', $formid)) {
    $fieldsets = $modx->fromJson($object->get('json'));
    $form = $object->toArray();
    $hide = isset($form['extended']['hide_from_previews']) && !empty($form['extended']['hide_from_previews']) ? true : false;

    if (!$hide) {
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

                switch ($field['type']) {
                    case 'checkbox':
                        $field['value'] = explode('||', $request[$field['name']]);
                        break;
                    default:
                        $field['value'] = $request[$field['name']];
                        break;
                }


                $opts = array();
                $options = $modx->fromJson($field['inputoptions']);
                if (is_array($options)) {
                    foreach ($options as $option) {
                        $option['name'] = $field['name'];
                        if (is_array($field['value'])) {
                            $checked = in_array($option['value'], $field['value']) ? 'checked="checked"' : '';
                            $selected = in_array($option['value'], $field['value']) ? 'selected="selected"' : '';
                        } else {
                            $checked = $option['value'] == $field['value'] ? 'checked="checked"' : '';
                            $selected = $option['value'] == $field['value'] ? 'selected="selected"' : '';
                        }
                        $option['selected'] = $selected;
                        $option['checked'] = $checked;
                        $option['label'] = !empty($option['label']) ? $option['label'] : $option['value'];
                        $opts[] = $option;
                    }
                } else {
                    $options = explode('||', $field['inputoptions']);
                    foreach ($options as $option) {
                        $optparts = explode('==', $option);
                        $opt['name'] = $field['name'];
                        $opt['label'] = $optparts[0];
                        if (is_array($field['value'])) {
                            $checked = in_array($opt['value'], $field['value']) ? 'checked="checked"' : '';
                            $selected = in_array($opt['value'], $field['value']) ? 'selected="selected"' : '';
                        } else {
                            $checked = $opt['value'] == $field['value'] ? 'checked="checked"' : '';
                            $selected = $opt['value'] == $field['value'] ? 'selected="selected"' : '';
                        }
                        $opt['selected'] = $selected;
                        $opt['checked'] = $checked;
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


}

//$bloxdatas['bloxoutput'] = print_r($fieldset,1);
