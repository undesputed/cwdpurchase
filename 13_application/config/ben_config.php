<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['site_title'] = "Material Management System";

//
//
//barging
//equipment availability
//draft survey
//breakdown monitoring
//hr > count(drivers)
//mining operation ( mine to stockyard)(mine to port)
//port operation
//
//

$config['limit'] = 10;

/**Production**/
$config['year'] = '2014';
$config['truck_factor'] = array(
		'BARGING'=>array('DT'=>'18','ADT'=>'36'),
		'PRODUCTION'=>array('DT'=>'14','ADT'=>'28'),
	);

	
	$patern[] = array('data'=>array('MINE YARD','MINE YARD'),'operation'=>'Production','selected'=>'');
	$patern[] = array('data'=>array('MINEYARD 2','MINEYARD 2'),'operation'=>'Production','selected'=>'');

	
	/*$patern[] = array('data'=>array('MINE YARD','LOCATION J','SAMPLING 6'),'operation'=>'Transfer','selected'=>'');*/
	$patern[] = array('data'=>array('MINE YARD','LOCATION J','SAMPLING 6','SAMPLING 7'),'operation'=>'Transfer','selected'=>'');

	
	#$patern[] = array('data'=>array('MINEYARD 2','MINE YARD'),'operation'=>'Production','selected'=>'');
	$patern[] = array('data'=>array('MINEYARD 2','SAMPLING 6'),'operation'=>'Production','selected'=>'');
	$patern[] = array('data'=>array('MINEYARD 2','MINEYARD 2','MINE YARD'),'operation'=>'Production','selected'=>'');
	$patern[] = array('data'=>array('MINEYARD 2','LOCATION I','SAMPLING 6'),'operation'=>'Production','selected'=>'2'); //back -1
	$patern[] = array('data'=>array('MINE YARD','LOCATION J','SAMPLING 6','LOCATION J'),'operation'=>'Production','selected'=>'2');//back -1
	$patern[] = array('data'=>array('MINE YARD','LOCATION J','SAMPLING 6','SAMPLING 6'),'operation'=>'Production','selected'=>'2');//back -1
	
	$patern[] = array('data'=>array('LOCATION J','MINE YARD'),'operation'=>'Production','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 6'),'operation'=>'Production','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 9'),'operation'=>'Production','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 6','SAMPLING 9'),'operation'=>'Production','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 6','SAMPLING 6'),'operation'=>'Production','selected'=>'1');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 6','SAMPLING 7','SAMPLING 6'),'operation'=>'Production','selected'=>'');	
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 6','LOCATION J'),'operation'=>'Production','selected'=>'1');		
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 5','SAMPLING 6'),'operation'=>'Production','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 5','LOCATION I'),'operation'=>'Production','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 5','SAMPLING 6','SAMPLING 9'),'operation'=>'Production','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 9','LOCATION J'),'operation'=>'Production','selected'=>'1');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 5','LOCATION J'),'operation'=>'Production','selected'=>'1');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 9','SAMPLING 7'),'operation'=>'Production','selected'=>'1');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 9','SAMPLING 6'),'operation'=>'Production','selected'=>'1');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 9','SAMPLING 5'),'operation'=>'Production','selected'=>'1');



	$patern[] = array('data'=>array('LOCATION I','SAMPLING 5','SAMPLING 6','SAMPLING 9'),'operation'=>'Production','selected'=>'');
	$patern[] = array('data'=>array('LOCATION I','SAMPLING 6','LOCATION I'),'operation'=>'Production','selected'=>'1');
	$patern[] = array('data'=>array('LOCATION I','SAMPLING 5','SAMPLING 9'),'operation'=>'Production','selected'=>'');
	$patern[] = array('data'=>array('LOCATION I','SAMPLING 6','LOCATION J'),'operation'=>'Production','selected'=>'1');
	$patern[] = array('data'=>array('LOCATION I','SAMPLING 9','LOCATION J'),'operation'=>'Production','selected'=>'1');
	$patern[] = array('data'=>array('LOCATION I','SAMPLING 9','SAMPLING 6'),'operation'=>'Production','selected'=>'1');
	$patern[] = array('data'=>array('LOCATION I','SAMPLING 6','SAMPLING 6'),'operation'=>'Production','selected'=>'1');
	$patern[] = array('data'=>array('LOCATION I','SAMPLING 6'),'operation'=>'Production','selected'=>'');
	
	
	/**Direct**/

	$patern[] = array('data'=>array('LOCATION I','SAMPLING 5','SAMPLING 6','PY 7','SAMPLING B'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION I','SAMPLING 6','SAMPLING B'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION I','SAMPLING 6','PY 7','SAMPLING B','SAMPLING A'),'operation'=>'Direct','selected'=>'');		
	$patern[] = array('data'=>array('LOCATION I','PY 7','SAMPLING A'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION I','SAMPLING 6','PY 7','SAMPLING A'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION I','SAMPLING 9','SAMPLING B'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION I','SAMPLING 9','SAMPLING A'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION I','SAMPLING 6','SAMPLING 7','PY 7','SAMPLING B'),'operation'=>'Direct','selected'=>'');


	$patern[] = array('data'=>array('LOCATION J','SAMPLING 7','PY 7','SAMPLING B'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 6','PY 7','SAMPLING B'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 6','SAMPLING 7','PY 7','SAMPLING B'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 9','PY 7','SAMPLING B','SAMPLING A'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','PY 7','SAMPLING B','SAMPLING A'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 6','SAMPLING B','SAMPLING A'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 6','PY 7','SAMPLING B','SAMPLING A'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING B','SAMPLING A'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 6','PY 7','SAMPLING A'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 6','SAMPLING A'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 5','PY 7','SAMPLING A'),'operation'=>'Direct','selected'=>'');


	
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 9','SAMPLING B'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING A'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 9','SAMPLING 7','PY 7','SAMPLING B','SAMPLING A'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 9','SAMPLING A'),'operation'=>'Direct','selected'=>'');		
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 6','SAMPLING 7','PY 7','SAMPLING A'),'operation'=>'Direct','selected'=>'');
	$patern[] = array('data'=>array('LOCATION J','SAMPLING 6','SAMPLING 7','SAMPLING B'),'operation'=>'Direct','selected'=>'');


	/**Barging**/
	$patern[] = array('data'=>array('PY 7','SAMPLING B','SAMPLING A','SAMPLING B','PY 7'),'operation'=>'Barging','selected'=>'2');//back -1
	$patern[] = array('data'=>array('SAMPLING 9','SAMPLING 6','SAMPLING 7','PY 7','SAMPLING A'),'operation'=>'Barging','selected'=>'');
	$patern[] = array('data'=>array('SAMPLING 6','SAMPLING 7','PY 7','SAMPLING A'),'operation'=>'Barging','selected'=>'');
	$patern[] = array('data'=>array('SAMPLING 6','SAMPLING 7','SAMPLING A'),'operation'=>'Barging','selected'=>'');
	$patern[] = array('data'=>array('SAMPLING 5','SAMPLING 7','SAMPLING A'),'operation'=>'Barging','selected'=>'');		
	
	$patern[] = array('data'=>array('SAMPLING 6','SAMPLING 7','SAMPLING B'),'operation'=>'Barging','selected'=>'');
	$patern[] = array('data'=>array('SAMPLING 7','PY 7','SAMPLING B','SAMPLING A','SAMPLING B','PY 7'),'operation'=>'Barging','selected'=>'3');//back - 2
	$patern[] = array('data'=>array('SAMPLING 7','PY 7','SAMPLING A','SAMPLING B'),'operation'=>'Barging','selected'=>'2');
	$patern[] = array('data'=>array('SAMPLING 7','PY 7','SAMPLING B','SAMPLING A','PY 7'),'operation'=>'Barging','selected'=>'3');
	$patern[] = array('data'=>array('SAMPLING 7','SAMPLING A'),'operation'=>'Barging','selected'=>'');

$config['pattern'] = $patern;

$config['accounting'] = array(
						'construction_inventory'=>'10',
						'vat_input_tax'=>'30',
						'accounts_payable'=>'42',						
						);
