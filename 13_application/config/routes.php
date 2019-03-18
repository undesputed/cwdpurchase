<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/


$route['accounting'] = "accounting/main";
$route['accounting/(:any)'] = "accounting/main/$1";

$route['accounting_entry/(:any)'] = "accounting/$1";
$route['accounting_entry/(:any)/(:any)'] = "accounting/$1/$2";

$route['print'] = "print/print_controller";
$route['print/(:any)'] = "print/print_controller/$1";

$route['boq'] = "boq/boq";
$route['boq/(:any)'] = "boq/boq/$1";

$route['transaction/(:any)/(:any)'] = "procurement/do_notification/$1/$2";
$route['transaction/(:any)'] = "procurement/do_notification/$1";

$route['transaction_list/(:any)/(:any)'] = "procurement/transaction_list/$1/$2";
$route['transaction_list/(:any)'] = "procurement/transaction_list/$1";

$route['monitoring/(:any)'] = "monitoring/$1";
$route['dashboard/(:any)'] = "dashboard/$1";

$route['service_desk'] = "service_desk/service_desk";
$route['service_desk/(:any)'] = "service_desk/service_desk/$1";

$route['reports'] = "reports/reports";
$route['reports/(:any)'] = "reports/reports/$1";

$route['manage'] = "manage/manage";
$route['manage/(:any)'] = "manage/manage/$1";

$route['manage_report'] = "manage_report/manage_report";
$route['manage_report/(:any)'] = "manage_report/manage_report/$1";

$route['report_details'] = "manage_report/report_details";
$route['report_details/(:any)'] = "manage_report/report_details/$1";

$route['truck'] = "manage_report/truck";
$route['truck/(:any)'] = "manage_report/truck/$1";

$route['truck_monitoring'] = "truck_monitoring/truck_monitoring";
$route['truck_monitoring/(:any)'] = "truck_monitoring/truck_monitoring/$1";

$route['production_report'] = "reports/mine_operation";
$route['production_report/(:any)'] = "reports/mine_operation/$1";

$route['shipment_operation'] = "reports/shipment_operation";
$route['shipment_operation/(:any)'] = "reports/shipment_operation/$1";

$route['daily-project-report'] = "daily_production_report/daily_production_report";
$route['daily-project-report/(:any)'] = "daily_production_report/daily_production_report/$1";

$route['maintenance'] = "maintenance/maintenance";
$route['maintenance/(:any)'] = "maintenance/maintenance/$1";

$route['dispatch'] = "dispatch/dispatch";
$route['dispatch/(:any)'] = "dispatch/dispatch/$1";

$route['hr'] = "hr/hr";
$route['hr/(:any)'] = "hr/hr/$1";

$route['tank-fuel-monitoring'] = "tank_fuel/tank_fuel";
$route['tank-fuel-monitoring/(:any)'] = "tank_fuel/tank_fuel/$1";

$route['material-inventory'] = "inventory/material_inventory";
$route['material-inventory/(:any)'] = "inventory/material_inventory/$1";

$route['user'] = "manage/user";
$route['user/(:any)'] = "manage/user/$1";

$route['rf'] = "map/map";
$route['rf/(:any)'] = "map/map/$1";

$route['map/(:any)'] = "map/map/$1";


$route['equipment_history'] = "equipment_history/equipment_history";
$route['equipment_history/(:any)'] = "equipment_history/equipment_history/$1";


$route['vessel_summary'] = "vessel/vessel";
$route['vessel_summary/(:any)'] = "vessel/vessel/$1";

$route['default_controller'] = "material_mgt/main";
$route['404_override'] = 'page_not_found/index';


/* End of file routes.php */
/* Location: ./application/config/routes.php */