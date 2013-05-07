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
