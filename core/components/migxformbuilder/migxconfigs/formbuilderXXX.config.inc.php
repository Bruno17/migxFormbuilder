<?php

//$this->modx->addPackage($this->bloxconfig['packagename'], $this->modx->getOption('core_path') . 'components/' . $this->bloxconfig['packagename'] . '/model/');

/*
$museumid = $this->modx->getOption('museumid',$_REQUEST,'');

//get museums for listbox
$classname = 'meMuseum';
$c = $this->modx->newQuery($classname);
$c->select($this->modx->getSelectColumns($classname,$classname,'',array('id','name')));
$c->where(array('accessibility_options:!=' => ''));
$c->sortby('name');
if ($collection = $this->modx->getCollection($classname,$c)){
$options = array();
foreach ($collection as $object){
$id = $object->get('id');
$name = $object->get('name');
$selected = $id == $museumid ? 'selected="selected"':'';
$options[] = '<option '.$selected.' value="'.$id.'">'.$name.'</option>';
}
$museumoptions = implode('',$options);
}
*/

if ($sender == 'default/fields') {

    $config = $this->customconfigs;
    $packageName = $config['packageName'];

    $packagepath = $this->modx->getOption('core_path') . 'components/' . $packageName . '/';
    $modelpath = $packagepath . 'model/';
    $this->modx->addPackage($packageName, $modelpath);

    $tabs = array();
    if ($object = $this->modx->getObject('mfbForm', array('name' => 'checklist'))) {
        $fieldsets = $this->modx->fromJson($object->get('json'));
        foreach ($fieldsets as $fieldset) {
            $tab['caption'] = $fieldset['name'];
            
            $fields = $this->modx->fromJson($fieldset['fields']);
            foreach ($fields as $field) {
                $tabfield['field']=$field['name'];
                $tabfield['caption']=$field['name'];
                $tabfield['description']=$field['label'];
                $tabfield['inputTVtype'] = $field['type'];
                switch ($field['type']) {
                    case 'radio':
                        $tabfield['inputOptionValues']=$field['inputoptions'];
                        $tabfield['inputTVtype'] = 'option';
                        break;
                    case 'checkbox':
                        $tabfield['inputOptionValues']=$field['inputoptions'];
                }

                $tab['fields'][]=$tabfield;
            }

            
            $tabs[]=$tab;
            
        }
    }


    $this->customconfigs['tabs'] = $tabs;

}

