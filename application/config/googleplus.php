<?php



defined('BASEPATH') or exit('No direct script access allowed');

/*

$config['googleplus']['application_name'] = '';

$config['googleplus']['client_id']        = '';

$config['googleplus']['client_secret']    = '';

$config['googleplus']['redirect_uri']     = '';

$config['googleplus']['api_key']          = '';

$config['googleplus']['scopes']           = array();

*/



/*

| -------------------------------------------------------------------

|  Google API Configuration

| -------------------------------------------------------------------

| 

| To get API details you have to create a Google Project

| at Google API Console (https://console.developers.google.com)

| 

|  client_id         string   Your Google API Client ID.

|  client_secret     string   Your Google API Client secret.

|  redirect_uri      string   URL to redirect back to after login.

|  application_name  string   Your Google application name.

|  api_key           string   Developer key.

|  scopes            string   Specify scopes

*/

$config['googleplus']['client_id'] = '';

$config['googleplus']['client_secret'] = '';

$config['googleplus']['redirect_uri'] = '';

$config['googleplus']['application_name'] = 'Todo';

// $config['googleplus']['api_key'] = 'AIzaSyA6MSobifD5lNMeuEuxsDVh7Lj2ehxzp8M';

$config['googleplus']['scopes'] = array('email', 'profile');
