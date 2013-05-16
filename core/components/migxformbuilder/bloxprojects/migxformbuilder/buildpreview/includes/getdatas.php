<?php

$modx->addPackage($this->bloxconfig['packagename'], $modx->getOption('core_path') . 'components/' . $this->bloxconfig['packagename'] . '/model/');

$formids = $modx->getOption('form_ids', $this->bloxconfig, '');
$task = $modx->getOption('task', $this->bloxconfig, '');
$placeholderPrefix = 'fi.';
$sessionVarKey = 'migxformrequest';
$formids = explode(',', $formids);

$values = $_SESSION[$sessionVarKey];

foreach ($formids as $formid) {


    if ($object = $modx->getObject('mfbForm', $formid)) {
        $pagedatas = $object->toArray();
        $fieldsets = $modx->fromJson($object->get('json'));

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
            $pagedatas['innerrows']['fieldset'][] = $fieldset;
        }


    }

    $bloxdatas['innerrows']['page'][] = $pagedatas;

}


//echo '<pre>'.print_r($pagedatas,1).'</pre>';
