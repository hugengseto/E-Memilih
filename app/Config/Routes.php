<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// HomeController
$routes->get('/', 'HomeController::index');
$routes->get('/tentang', 'HomeController::tentang');
// AuthController
$routes->get('/admin-login', 'AuthController::index');
// DashboardController
$routes->get('/admin/dashboard', 'DashboardController::index');
$routes->get('/admin/profil', 'DashboardController::profil');
// MediaController
$routes->get('/admin/media_pemilihan', 'MediaController::index');
$routes->get('/admin/media_pemilihan/tambah', 'MediaController::tambah');
$routes->get('/admin/media_pemilihan/detail/(:segment)', 'MediaController::detail/$1');
$routes->get('/admin/media_pemilihan/edit/(:segment)', 'MediaController::edit/$1');
// KandidatController
$routes->get('/admin/media_pemilihan/(:segment)/tambah_kandidat', 'KandidatController::tambah_kandidat/$1');
$routes->get('/admin/media_pemilihan/(:segment)/edit_kandidat/(:num)', 'KandidatController::edit_kandidat/$1/$2');
// PersertaController
$routes->get('/admin/media_pemilihan/(:segment)/tambah_peserta', 'PesertaController::tambah_peserta/$1');
$routes->get('/admin/media_pemilihan/(:segment)/upload_csv', 'PesertaController::upload_csv/$1');
$routes->get('/admin/media_pemilihan/(:segment)/edit_peserta/(:num)', 'PesertaController::edit_peserta/$1/$2');
// DownloadController
$routes->get('/admin/download/(:segment)', 'DownloadController::file_csv/$1');
$routes->get('/admin/export/peserta/(:segment)', 'DownloadController::export_csv/$1');
// HomeController
$routes->get('/(:segment)', 'HomeController::mediaPemilihan/$1');
$routes->get('/(:segment)/(:segment)', 'HomeController::konfirmasiPilihan/$1/$2');

$routes->post('/auth', 'AuthController::auth');
$routes->post('/logout', 'AuthController::logout');
$routes->post('/admin/media_pemilihan/aksi_tambah', 'MediaController::aksi_tambah');
$routes->post('/admin/media_pemilihan/aksi_tambah/kandidat/(:segment)', 'KandidatController::aksi_tambah/$1');
$routes->post('/admin/media_pemilihan/aksi_tambah/peserta/(:segment)', 'PesertaController::aksi_tambah/$1');
$routes->post('/admin/media_pemilihan/aksi_edit/(:segment)/peserta/(:num)', 'PesertaController::aksi_edit/$1/$2');
$routes->post('/admin/media_pemilihan/aksi_edit/(:segment)/kandidat/(:num)', 'KandidatController::aksi_edit/$1/$2');
$routes->post('/admin/media_pemilihan/aksi_edit/(:segment)', 'MediaController::aksi_edit/$1');
$routes->post('/admin/media_pemilihan/(:segment)/import_peserta', 'PesertaController::aksi_upload_csv/$1');
$routes->post('/admin/media_pemilihan/(:segment)/aksi_import', 'PesertaController::aksi_import/$1');
$routes->post('/admin/media_pemilihan/remove_media/(:num)', 'MediaController::remove/$1');
$routes->post('/admin/media_pemilihan/(:segment)/remove_peserta/(:num)', 'PesertaController::remove/$1/$2');
$routes->post('/admin/media_pemilihan/(:segment)/remove_kandidat/(:num)', 'KandidatController::remove/$1/$2');
$routes->post('/admin/media_pemilihan/(:segment)/remove_all_peserta', 'PesertaController::remove_all/$1');
// profil update
$routes->post('/admin/update/profil/(:segment)', 'DashboardController::update_profil/$1');
// aksi konfirmasi
$routes->post('/(:segment)/hak_pilih_id/(:segment)', 'HomeController::aksiKonfirmasiPilihan/$1/$2');
// route minta kode otp di whatsapp
$routes->post('/(:segment)/(:segment)/mintaKodeOtp', 'HomeController::mintaOtp/$1/$2');
