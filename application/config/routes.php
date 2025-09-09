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

$route['default_controller'] = "home";
$route['report/invoice-rating-report'] = "Report/invoiceRatingReport";
$route['report/service-summary-report'] = "Report/serviceSummaryReport";
$route['report/bike-receive-pending'] = "Report/reportBikeReceivePending";
$route['sdms-report/invoice-list'] = "Sdmsreport/invoiceList";
$route['sdms-report/invoice-details/(:any)(/:any)?'] = "Sdmsreport/invoiceDetails/$1$2";

$route['sdms-report/customer-ledger'] = "Sdmsreport/customerLedger";
$route['sdms-report/customer-ledger-details'] = "Sdmsreport/customerLedgerDetails";
$route['sdms-report/customer-wise-product-sold'] = "Sdmsreport/customerWiseProductSold";
$route['sdms-report/customer-wise-product-sold-details'] = "Sdmsreport/customerWiseProductSoldDetails";

//Service Center Job Statics Report
$route['jobcard/service-center-job-statics'] = "jobcard/ServiceCenterJobStatics";

$route['404_override'] = '';

$route['product-promotion-list']= 'ProductPromotion/promotionList';
$route['product-promotion']= 'ProductPromotion/addPromotion';
$route['promotion-top-sheet']= 'ProductPromotion/topSheet';
$route['promotion-report']= 'ProductPromotion/promotionReport';

// Affiliator
$route['jobcard/affiliator'] = 'jobcard/affiliator'; 
$route['jobcard/add-affiliator'] = 'jobcard/addAffiliator'; 
$route['jobcard/stor-affiliator'] = 'jobcard/storeAffiliator'; 
$route['jobcard/generate-affiliator-code'] = 'jobcard/generateAffiliatorCode'; 

// BRTA Registration Status
$route['logistics/BRTA-registration-status'] =  'logistics/BRTA_registrationStatus';
$route['logistics/BRTA-registration-status-list'] =  'logistics/BRTA_registrationStatusList';

/* End of file routes.php */
/* Location: ./application/config/routes.php */