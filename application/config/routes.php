<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Items

$route['/'] = 'items/index';
$route['items/(:num)'] = 'items/get_item/$1';
$route['items/sort']   = 'items/sort';
$route['items/sort_task']   = 'items/sort_task';

$route['items/export'] = 'items/export';
$route['items/export-sample-file'] = 'items/export_sample_file';
$route['items/import_timetable'] = 'items/import_timetable';
$route['items/import_task'] = 'items/import_task';

$route['items/add_owner']              = 'items/add_owner_to_project';
$route['items/add_owner']              = 'items/add_owner_to_project';
$route['items/add_owner_to_group']     = 'items/add_owner_to_group';

$route['items/add_department']         = 'items/add_department_to_project';
$route['items/delete_user_from_group'] = 'items/delete_user_from_group';
$route['items/get_owners']             = 'items/get_owners';
$route['items/get_fields']             = 'items/get_fields';
$route['items/save_filter']            = 'items/save_filter';
$route['items/addLink']                = 'items/add_link_zalo_for_group';
$route['items/get_meta']               = 'items/get_meta';
$route['items/update_all']             = 'items/update_all';
$route['items/search_item']            = 'items/search_item';
$route['items/search_department']      = 'items/search_department';
$route['items/search_users_input']     = 'items/search_users_input';
$route['items/get_childs']     = 'items/get_childs';
$route['items/file/(:any)/(:any)/(:any)']     = 'items/file_download/$1/$2/$3';

$route['admin/items/add']             = 'items/add';
$route['admin/items/group/add']       = 'items/add_group';
$route['admin/items/update/(:num)']   = 'items/update/$1';
$route['admin/items/delete/(:num)']   = 'items/delete/$1';
$route['admin/items/update_meta']     = 'items/update_meta';
$route['admin/items/delete_multiple'] = 'items/delete_multiple';

$route['admin/items/get_item_archive'] = 'items/get_item_is_archived';
$route['get_all_items']         = 'items/get_all_items';
$route['admin/items/restore/(:num)']   = 'items/restore/$1';

// Folder
$route['folder/view/(:num)']   = 'folder/view/$1';

// Table
$route['table/view/(:num)/(:num)']   = 'table/view/$1/$2';

$route['table/filter']               = 'table/filter';
$route['table/get_filter_item_html'] = 'table/get_filter_item_html';

// Fields
$route['fields']                = 'fields';
$route['fields/add']                = 'fields/add';
$route['fields/fetch']              = 'fields/fetchFields';
$route['fields/update/(:num)']      = 'fields/update/$1';
$route['fields/delete/(:any)']      = 'fields/delete/$1';
$route['fields/update_all']         = 'fields/update_all';
$route['fields/get_meta']           = 'fields/get_meta';
$route['fields/fetch_by_id/(:any)'] = 'fields/fetch_by_id/$1';

// TimeTable
$route['timetable/view/(:num)/(:num)']     = 'TimeTable/view/$1/$2';

// Schedule
$route['schedule']              = 'Schedule';
$route['schedule-user']         = 'Schedule/view';
$route['schedule/verify_recaptcha'] = 'Schedule/verify_recaptcha';
$route['schedule/search_schedule']  = 'Schedule/search_schedule';

//Gantt
$route['gantt/(:num)']           = 'Gantt/view/$1';
$route['gantt/view/(:num)/(:num)']      = 'Gantt/view/$1/$2';
$route['gantt/fetch_gantt_data/(:num)']      = 'Gantt/fetch_gantt_data/$1';

// Form
$route['form/add']         = 'form/add';
$route['form/view/(:num)/(:num)'] = 'form/view/$1/$2';

// Share Form
$route['share_form/add']    = 'ShareForm/add';
$route['share_form/(:any)'] = 'ShareForm/share_form/$1';

// Calendar
$route['calendar/add']           = 'calendar/add';
$route['calendar/update']        = 'calendar/update';
$route['calendar/view/(:num)/(:num)']   = 'calendar/view/$1/$2';
$route['calendar/events/(:num)'] = 'calendar/events/$1';

// Types
$route['admin/types/add']           = 'types/add';
$route['admin/types/update/(:any)'] = 'types/update/$1';
$route['admin/types/delete/(:any)'] = 'types/delete/$1';
$route['types/fetch']               = 'types/fetchTypes';
$route['types/fetch_by_id/(:any)']  = 'types/fetch_by_id/$1';

// Fields of type
$route['fields_of_type']        = 'FieldsOfType';
$route['fields_of_type/getall']         = 'FieldsOfType/getall';
$route['fields_of_type/add']            = 'FieldsOfType/add';
$route['fields_of_type/view/(:num)']    = 'FieldsOfType/view/$1';
$route['fields_of_type/update/(:num)']  = 'FieldsOfType/update/$1';
$route['fields_of_type/delete/(:num)']  = 'FieldsOfType/delete/$1';

//User
$route['user']                  = 'user';
$route['user/view']                 = 'user';
$route['user/fetch']                = 'user/fetchData';
$route['user/updatepassword']       = 'user/updatepassword';
$route['user/create']               = 'user/store';
$route['user/edit']                 = 'user/update';
$route['user/delete/(:any)']        = 'user/delete/$1';
$route['user/search_users']         = 'user/search_users';
// $route['user/search_users_input']    = 'user/search_users_input';

//Role
$route['role']                  = 'role';
$route['role/view']             = 'role';
$route['role/create']           = 'role/store';
$route['role/edit']             = 'role/update';
$route['role/delete/(:any)']    = 'role/delete/$1';

$route['permission/view']                   = 'permission/permission_list';
$route['permission']            = 'permission/permission_list';
$route['permission/create']                 = 'permission/permission_store';
$route['permission/edit']                   = 'permission/permission_update';
$route['permission/delete']                 = 'permission/permission_delete';
$route['permission/add_subpermission']      = 'permission/sub_permission_store';
$route['permission/addpermission/(:any)']   = 'permission/addpermission/$1';
$route['permission/edit_subpermission']     = 'permission/edit_subpermission';
$route['permission/delete_subpermission']   = 'permission/delete_subpermission';

//Auth
$route['login']                 = 'auth/show_login';
$route['auth/login']    = 'auth/login';
$route['logout']                = 'auth/logout';
$route['access_denied']         = 'auth/access_denied';

// File
$route['file/upload']        = 'file/upload';
$route['file/update_meta']   = 'file/update_meta';
$route['file/view/(:num)']   = 'file/view/$1';
$route['file/update/(:num)'] = 'file/update/$1';
$route['file/get_file']        = 'file/get_file';

//Logs
$route['logs']                  = 'logs/index';
$route['logs/get_all_logs']  = 'logs/get_all_logs';
$route['items/logs/(:num)']  = 'logs/get_logs/$1';
$route['items/logs/export']  = 'logs/logs_export';
$route['items/logs/restore'] = 'logs/restore';

// Html type
$route['html_type']             = "HtmlType";
$route['html_type/add']           = "HtmlType/create";
$route['html_type/view/(:num)']   = "HtmlType/view/$1";
$route['html_type/edit/(:num)']   = "HtmlType/edit/$1";
$route['html_type/delete/(:num)'] = "HtmlType/delete/$1";

$route['default_controller']    = 'items/index';
$route['404_override']          = '';
$route['translate_uri_dashes']  = FALSE;
$route['mywork']                = 'MyWork/index';

$route['setting/create'] = 'setting/create';
$route['setting/edit']   = 'setting/edit';
$route['setting/delete'] = 'setting/delete';

$route['setting/add'] = 'setting/add_setting';
$route['setting/update/(:num)'] = 'setting/update_value/$1';

// Conversation
$route['conversation/conversations_by_task']      = 'conversation/fetch_conversations_by_task';
$route['conversation/replies_by_conversation']    = 'conversation/fetch_replies_by_conversation';
$route['conversation/add_conversation']           = 'conversation/create';
$route['conversation/delete_conversation']        = 'conversation/delete';
$route['conversation/like_conversation']          = 'conversation/like';
$route['conversation/unlike_conversation']        = 'conversation/unlike';
$route['conversation/update_conversation/(:num)'] = 'conversation/update/$1';

//Score
$route['score']                 = 'score';
$route['score/export'] = 'score/export';
$route['confirm/mark'] = 'score/mark';
$route['confirm/show_review_modal'] = 'score/show_review_modal';

//Item role
$route['items_role/add']                            = 'itemsrole/add';
$route['items_role/delete']                         = 'itemsrole/delete';
$route['items_role/set_permission']                 = 'itemsrole/set_permission';
$route['items_role/get_role_permissions']           = 'itemsrole/get_role_permissions';
$route['items_role/get_owners_by_group']            = 'itemsrole/get_owners_by_group';
$route['items_role/add_users_to_role']              = 'itemsrole/add_users_to_role';
$route['items_role/add_user_to_pending_role']       = 'itemsrole/add_user_to_pending_role';
$route['items_role/approve']                        = 'itemsrole/approve';
$route['items_role/reject']                        = 'itemsrole/reject';
$route['items_role/remove']                        = 'itemsrole/remove';
$route['items_role/update_user_role']              = 'itemsrole/update_user_role';
$route['items_role/add_user_role']              = 'itemsrole/add_user_role';


$route['score/fetch_overview_data'] = 'score/fetch_overview_data';
$route['score/fetch_bar_chart_data'] = 'score/fetch_bar_chart_data';
$route['score/fetch_pie_chart_data'] = 'score/fetch_pie_chart_data';

//Notification
$route['notification/countNotification'] = 'notification/countNotification';
$route['notification/sendNotification'] = 'notification/sendNotification';
$route['notification/fetchUnreadNotifications'] = 'notification/fetchUnreadNotifications';
$route['notification/fetchNotifications'] = 'notification/fetchNotifications';
$route['notification/readNotification'] = 'notification/readNotification';
$route['notification/readNotifications'] = 'notification/readNotifications';
