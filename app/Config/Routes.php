<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

$routes->add('home', 'Home::index');
$routes->add('Home', 'Home::index');
$routes->add('faqs', 'Home::faqs');
$routes->add('new-mems/(:num)', 'Staff::new_mems');
$routes->add('new-mems/', 'Staff::new_mems');
$routes->add('master-test', 'Master::master_test');
$routes->add('master-edit-faq', 'Master::edit_faq');
$routes->add('master-edit-faq/(:num)', 'Master::edit_faq');
$routes->add('admin-edit-faq', 'Admin::edit_faq');
$routes->add('admin-edit-faq/(:num)', 'Admin::edit_faq');
$routes->add('master-faqs', 'Master::master_faqs');
$routes->add('admin-faqs', 'Admin::master_faqs');
$routes->add('delete-faq/(:num)', 'Master::delete_faq');
$routes->add('admin-delete-faq/(:num)', 'Admin::delete_faq');
$routes->add('regex', 'Master::regex');
$routes->add('contact', 'Home::contact');
$routes->add('show-update', 'Member::show_update');
$routes->add('show-mem/(:num)', 'Staff::show_mem');
$routes->add('res-mem/(:num)', 'Master::res_mem');
$routes->add('do-update', 'Member::do_update');
$routes->add('master-search', 'Master::search');
$routes->add('admin-search', 'Admin::search');
$routes->add('staff-search', 'Staff::search');
$routes->add('member-search', 'Member::search');
$routes->add('send-contact', 'Home::send_contact');
$routes->add('staff', 'Staff::index');
$routes->add('member', 'Member::index');
$routes->add('member/(:any)', 'Member::index');
$routes->add('master', 'Master::index');
$routes->add('admin', 'Admin::index');
$routes->add('login', 'Login::index');
$routes->add('pers-data', 'Member::pers_data');
$routes->add('add-mem', 'Master::add_mem');
$routes->add('add-mem/(:any)', 'Member::add_member');
$routes->add('add-fam-mem/(:num)', 'Member::add_fam_mem');
$routes->add('add-fam/(:num)', 'Master::add_fam_mem');
$routes->add('add-spouse/(:num)', 'Master::add_spouse');

$routes->add('send-mail', 'Home::send_mail');

$routes->add('admin-add-mem', 'Admin::add_mem');
$routes->add('admin-add-mem/(:any)', 'Admin::add_member');
$routes->add('admin-add-fam-mem/(:num)', 'Admin::add_fam_mem');
$routes->add('admin-fam/(:num)', 'Admin::add_fam_mem');
$routes->add('admin-add-spouse/(:num)', 'Admin::add_spouse');

$routes->add('payment-report', 'Admin::payment_report');
$routes->add('proc-payments-report', 'Admin::proc_payments_report');
$routes->add('edit-payment/(:num)', 'Admin::edit_payment');

$routes->add('staff-report', 'Staff::staff_report');
$routes->add('staff-report/(:alphanum)', 'Staff::staff_report');
$routes->add('edit-mem', 'Master::edit_mem');
$routes->add('admin-edit-mem', 'Admin::edit_mem');
$routes->add('edit-mem/(:num)', 'Master::edit_mem');
$routes->add('admin-edit-mem/(:num)', 'Admin::edit_mem');
$routes->add('update-mem/(:num)', 'Member::update_mem');
$routes->add('delete-mem/(:num)', 'Master::delete_mem');

$routes->add('admin-delete-mem/(:num)', 'Admin::delete_mem');

$routes->add('delete-fam-mem/(:num)', 'Member::delete_fam_mem');
$routes->add('delete-fam/(:num)', 'Master::delete_fam_mem');
$routes->add('edit-fam-mem/(:num)', 'Member::edit_fam_mem');
$routes->add('edit-fam/(:num)', 'Master::edit_fam_mem');
$routes->add('un-delete-mem/(:num)', 'Master::un_delete_mem');
$routes->add('purge-mem/(:num)', 'Master::purge_mem');

$routes->add('admin-delete-fam-mem/(:num)', 'Admin::delete_fam_mem');
$routes->add('admin-delete-fam/(:num)', 'Admin::delete_fam_mem');
$routes->add('admin-edit-fam-mem/(:num)', 'Admin::edit_fam_mem');
$routes->add('admin-edit-fam/(:num)', 'Admin::edit_fam_mem');
$routes->add('admin-un-delete-mem/(:num)', 'Admin::un_delete_mem');
$routes->add('admin-purge-mem/(:num)', 'Admin::purge_mem');

$routes->add('set-silent-key/(:num)', 'Master::set_silent');
$routes->add('admin-set-silent-key/(:num)', 'Admin::set_silent');

$routes->add('load-silent/(:num)', 'Staff::load_silent');
$routes->add('unset-silent-key/(:num)', 'Master::unset_silent');

$routes->add('admin-unset-silent-key/(:num)', 'Admin::unset_silent');

$routes->add('members-edit', 'Master::show_members');
$routes->add('members', 'Master::show_members');
$routes->add('members/(:num)', 'Master::show_members');
$routes->add('admin-members/(:num)', 'Admin::show_members');
$routes->add('members-list', 'Staff::show_members');
$routes->add('members-list/(:num)', 'Staff::show_members');

$routes->add('admin-members-edit', 'Admin::show_members');
$routes->add('admin-members', 'Admin::show_members');
$routes->add('admin-members/(:num)', 'Admin::show_members');

$routes->add('man-payment/(:num)', 'Admin::man_payment');

$routes->add('logout', 'Home::logout');
$routes->add('register', 'Home::register');
$routes->add('renew', 'Member::renew');
$routes->add('send-renew/(:num)', 'Member::do_renew');
$routes->add('print-dir', 'Staff::print_dir');
$routes->add('print-callsigns', 'Staff::print_callsigns');

$routes->add('mem_print-dir', 'Member::print_dir');
$routes->add('mem_print-callsigns', 'Member::print_callsigns');

$routes->add('edit-users', 'Master::edit_users');
$routes->add('admin-edit-users', 'Admin::edit_users');
$routes->add('delete-user/(:num)', 'Master::delete_user');
$routes->add('admin-delete-user/(:num)', 'Admin::delete_user');
$routes->add('set-pass/(:alphanum)', 'Home::set_pass');
$routes->add('load-user/(:num)', 'Home::load_usr');
$routes->add('load-admin/(:num)', 'Master::load_admin');
$routes->add('admin-load-admin/(:num)', 'Admin::load_admin');

$routes->add('activate/(:num)', 'Master::activate');
$routes->add('authorize/(:num)', 'Master::authorize');
$routes->add('deactivate/(:num)', 'Master::activate');
$routes->add('unauthorize/(:num)', 'Master::authorize');
$routes->add('reset-user/(:num)', 'Master::reset_user');

$routes->add('admin-activate/(:num)', 'Admin::activate');
$routes->add('admin-authorize/(:num)', 'Admin::authorize');
$routes->add('admin-deactivate/(:num)', 'Admin::activate');
$routes->add('admin-unauthorize/(:num)', 'Admin::authorize');
$routes->add('admin-user/(:num)', 'Admin::reset_user');

$routes->add('admin-reset-user/(:num)', 'Admin::reset_user');
$routes->add('set-user-pass/(:num)', 'Home::set_user_pass');
$routes->add('change-user-pass/(:alphanum)', 'Home::change_user_pass');
$routes->add('set-user/:alphanum', 'Home::set_user');
$routes->add('change-pass/:alphanum', 'Home::change_pass');
$routes->add('send-reg', 'Home::send_reg');
$routes->add('reset-password', 'Home::reset_password');
$routes->add('load-pass', 'Home::load_password');
$routes->add('lost-username', 'Home::lost_username');
$routes->add('change-username', 'Home::lost_username');
$routes->add('email-username', 'Home::email_username');
$routes->add('load-username/(:num)', 'Home::load_username');
$routes->add('new-username', 'Home::new_username');
$routes->add('set-username', 'Home::set_username');
$routes->add('confirm-membership', 'Home::confirm_mem');
$routes->add('convert-call', 'Master::convert_callsign');
$routes->add('master', 'Master::index');
$routes->add('load-pers/(:num)', 'Member::load_pers');

//Routes to process payments - v3
$routes->add('go-pay/(:alphanum)', 'Member::go_pay');
$routes->add('go-donate/(:alphanum)', 'Member::go_donate');
$routes->add('process-pub-mem', 'Member::process_mem');
$routes->add('new-member', 'Home::welcome_new');
$routes->add('public-renew', 'Home::public_renew');
$routes->add('public-renewdon', 'Home::public_renewdon');
$routes->add('donate', 'Home::donate');
$routes->add('terms', 'Home::terms');

$routes->add('test-email', 'Home::test_email');

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
