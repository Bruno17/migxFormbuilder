<?php
$xpdo_meta_map['mfbForm']= array (
  'package' => 'migxformbuilder',
  'version' => '1.1',
  'table' => 'migxformbuilder_forms',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => '',
    'json' => '',
    'sendmail' => 0,
    'todb' => 0,
    'sendautoresponse' => 0,
    'fiarText' => '',
    'extended' => '',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'json' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'sendmail' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'todb' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'sendautoresponse' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'fiarText' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'extended' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => false,
      'default' => '',
    ),
  ),
  'aggregates' => 
  array (
    'FormRequests' => 
    array (
      'class' => 'mfbFormRequest',
      'local' => 'id',
      'foreign' => 'form_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
