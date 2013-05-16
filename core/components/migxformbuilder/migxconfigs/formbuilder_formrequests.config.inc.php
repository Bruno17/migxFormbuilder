<?php

if ($sender == 'default/fields') {
    $config = $this->modx->migx->customconfigs;
    $prefix = isset($config['prefix']) && !empty($config['prefix']) ? $config['prefix'] : null;

    if (isset($config['use_custom_prefix']) && !empty($config['use_custom_prefix'])) {
        $prefix = isset($config['prefix']) ? $config['prefix'] : '';
    }
    $packageName = $config['packageName'];

    $packagepath = $this->modx->getOption('core_path') . 'components/' . $packageName . '/';
    $modelpath = $packagepath . 'model/';

    $this->modx->addPackage($packageName, $modelpath, $prefix);
    $classname = $config['classname'];

    $object_id = $this->modx->getOption('object_id', $_REQUEST, 0);

    $formtab = '
    {
      "caption":"",
      "print_before_tabs":"0",
      "fields":[
        {
          "MIGX_id":1,
          "field":"",
          "caption":"",
          "description":"",
          "description_is_code":1,
          "inputTV":"",
          "inputTVtype":"",
          "configs":"",
          "sourceFrom":"config",
          "sources":"[]",
          "inputOptionValues":"",
          "default":""
        }
      ]
    }';
    $tabs = array();
    
    if ($object = $this->modx->getObject($classname, $object_id)) {
        $form_ids = $object->get('form_ids');
        if (!empty($form_ids)) {
            $form_ids = explode(',', $form_ids);
        } else {
            $form_ids = array();
            $form_ids[] = $object->get('form_id');
        }

        
        foreach ($form_ids as $form_id) {

            $params['packagename'] = 'migxformbuilder';
            $params['component'] = 'migxformbuilder';
            $params['project'] = 'migxformbuilder';
            $params['task'] = 'viewformrequest';
            $params['form_id'] = $form_id;
            $params['object_id'] = $object_id;
            $tab = $this->modx->fromJson($formtab);
            $tab['caption'] = $form_id; 
            $tab['fields'][0]['description'] = $this->modx->runSnippet('bloX', $params);
            $tabs[] = $tab;

        }
    }

    $this->customconfigs['tabs'] = $tabs;


}
