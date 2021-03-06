/*
 * FormIt2db/db2FormIt
 * 
 * Copyright 2013 by Thomas Jakobi <thomas.jakobi@partout.info>
 * 
 * The snippets bases on the code in the following thread in MODX forum 
 * http://forums.modx.com/thread/?thread=32560 
 * 
 * FormIt2db/db2FormIt is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * FormIt2db/db2FormIt is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more 
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * FormIt2db/db2FormIt; if not, write to the Free Software Foundation, Inc., 
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package formit2db
 * @subpackage formit2db snippet
 */
$prefix = $modx->getOption('prefix', $scriptProperties, $modx->getOption(xPDO::OPT_TABLE_PREFIX), true);
$packagename = $modx->getOption('packagename', $scriptProperties, '', true);
$classname = $modx->getOption('classname', $scriptProperties, '', true);
$where = $modx->fromJson($modx->getOption('where', $scriptProperties, '', true));
$paramname = $modx->getOption('paramname', $scriptProperties, '', true);
$fieldname = $modx->getOption('fieldname', $scriptProperties, $paramname, true);
$arrayFormat = $modx->getOption('arrayFormat', $scriptProperties, 'csv', true);
$arrayFields = $modx->fromJson($modx->getOption('arrayFields', $scriptProperties, '[]', true));
$removeFields = $modx->fromJson($modx->getOption('removeFields', $scriptProperties, '[]', true));

$packagepath = $modx->getOption('core_path') . 'components/' . $packagename . '/';
$modelpath = $packagepath . 'model/';

$modx->addPackage($packagename, $modelpath, $prefix);

if ($fieldname) {
	if (is_array($where)) {
		$where[$fieldname] = $_POST[$paramname];
	} else {
		$where = array($fieldname => $_POST[$paramname]);
	}
}

if (is_array($where)) {
	$dataobject = $modx->getObject($classname, $where);
	if (empty($dataobject)) {
		$dataobject = $modx->newObject($classname);
	}
} else {
	$dataobject = $modx->newObject($classname);
}

if (!is_object($dataobject) || !($dataobject instanceof xPDOObject)) {
	$errorMsg = 'Failed to create object of type: ' . $classname;
	$hook->addError('error_message', $errorMsg);
	return false;
}

$formFields = $hook->getValues();
$extended = array();
foreach ($formFields as $field => $value) {
	if (!in_array($field, $removeFields)) {
		if (in_array($field, $arrayFields)) {
			switch ($arrayFormat) {
				case 'json': {
						$value = json_encode($value);
						break;
					}
				case 'csv' :
				default : {
						$value = implode(',', $value);
						break;
					}
			}
		}
        
        $parts = explode('_',$field);
        if ($parts[0] == 'extended' && isset($parts[1])){
            unset($parts[0]);
            $extended[implode('_',$parts)] = $value;
        }
        
		$dataobject->set($field, $value);
	}
}

if (count($extended)>0){
    $dataobject->set('extended', $modx->toJson($extended));    
}

$dataobject->set('createdon', strftime('%Y-%m-%d %H:%M:%S')); 

if (!$dataobject->save()) {
	$errorMsg = 'Failed to save object of type: ' . $classname;
	$hook->addError('error_message', $errorMsg);
	return false;
}
return true;