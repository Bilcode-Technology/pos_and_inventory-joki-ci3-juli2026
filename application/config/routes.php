<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
*/

$route['default_controller'] = 'dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| AUTHENTICATION ROUTES
| -------------------------------------------------------------------------
*/
$route['login'] = 'auth/login';
$route['register'] = 'auth/register';
$route['logout'] = 'auth/logout';

/*
| -------------------------------------------------------------------------
| MASTER DATA ROUTES (Users, Kategori, Produk)
| -------------------------------------------------------------------------
*/
$route['users'] = 'users/index';
$route['kategori'] = 'kategori/index';
$route['produk'] = 'produk/index';

/*
| -------------------------------------------------------------------------
| TRANSACTION / SALES ROUTES (POS & Penjualan)
| -------------------------------------------------------------------------
*/
$route['pos'] = 'penjualan/pos';
$route['penjualan'] = 'penjualan/index';
$route['penjualan/detail/(:num)'] = 'penjualan/detail/$1';

/*
| -------------------------------------------------------------------------
| INVENTORY ROUTES (Supplier, Pembelian, Riwayat Stok)
| -------------------------------------------------------------------------
*/
$route['supplier'] = 'supplier/index';
$route['pembelian/create'] = 'pembelian/create';
$route['pembelian'] = 'pembelian/index';
$route['pembelian/detail/(:num)'] = 'pembelian/detail/$1';
$route['riwayat-stok'] = 'riwayat_stok/index';
