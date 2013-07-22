<?php

$gridcontextmenus['viewdetails']['code']="
        m.push({
            className : 'update', 
            text: 'View Details',
            handler: 'this.update'
        });
        m.push('-');
";
$gridcontextmenus['viewdetails']['handler'] = 'this.update';

$gridcontextmenus['printdetails']['code']="
        m.push({
            className : '', 
            text: 'Print',
            handler: 'this.printdetails'
        });
        m.push('-');
";
$gridcontextmenus['printdetails']['handler'] = 'this.printdetails';

$printpage = $this->modx->getOption('migxformbuilder.print_page');
$printurl = $this->modx->makeUrl($printpage,'web');

$gridfunctions['this.printdetails'] = "
printdetails: function() {
        window.open('".$printurl."?object_id='+this.menu.record.id,'Print','scrollbars=yes,width=800,height=600,resizable=yes');
    }	
";


