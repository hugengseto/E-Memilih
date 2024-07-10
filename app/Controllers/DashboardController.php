<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KandidatModel;
use App\Models\MediaModel;
use App\Models\PesertaModel;
use App\Models\UserModel;
use CodeIgniter\Commands\Server\Serve;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use DateTime;

class DashboardController extends BaseController
{

    public function index()
    {
        // inisialisasi model
        $modalMediaPemilihan = new MediaModel();
        $modelKandidat       = new KandidatModel();
        $modelPeserta       = new PesertaModel();

        // ambil data
        $media = $modalMediaPemilihan->findAll();
        $kandidat = $modelKandidat->findAll();
        $peserta = $modelPeserta->findAll();
        $data = [
            'title' => "Dashboard | Admin E-Memilih",
            'media' => $media,
            'kandidat' => $kandidat,
            'peserta' => $peserta,
            'getStatus' => function ($mulai, $batas) {
                return $this->_getStatus($mulai, $batas);
            }
        ];

        return view('dashboard/index', $data);
    }

    // ambil status pemilihan
    private function _getStatus($mulai = null, $batas = null)
    {
        $today = new DateTime();
        $startDate = new DateTime($mulai);
        $endDate = new DateTime($batas);

        if ($endDate < $today) {
            return 'Selesai'; // Jika tanggal selesai sudah lewat
        } elseif ($startDate <= $today && $endDate >= $today) {
            // Cek jam saat ini
            $currentTime = new DateTime();
            if ($currentTime >= $startDate && $currentTime <= $endDate) {
                return 'Berlangsung'; // Jika saat ini berada di antara tanggal mulai dan selesai
            } else {
                return 'Mendatang'; // Jika masih mendekati tanggal mulai atau selesai
            }
        } else {
            return 'Mendatang'; // Jika tanggal masih mendekati mulai
        }
    }

    public function profil()
    {
        // inisialisasi
        $userModel = new UserModel();

        $data = [
            'title'     => 'Profil | Admin E-Memilih',
            'user'      => $userModel->where('full_name', session('username'))->first()
        ];

        return view('dashboard/profil', $data);
    }

    public function update_profil($username)
    {
        $validation = Services::validation();
        $validation->setRules([
            'username' => [
                'rules'     => 'required',
                'errors'    => [
                    'required' => 'Kolom Username tidak boleh kosong.'
                ]
            ],
            'email' => [
                'rules'     => 'required',
                'errors'    => [
                    'required' => 'Kolom Email tidak boleh kosong.'
                ]
            ],
            'oldPassword' => [
                'rules'     => 'required',
                'errors'    => [
                    'required' => 'Kolom Old Password tidak boleh kosong.'
                ]
            ]
        ]);

        if (!$validation->run($this->request->getPost())) {
            return redirect()->to(base_url('/admin/profil'))->withInput()->with('errors', $validation->getErrors());
        }

        // inisialisasi model 
        $modelUser = new UserModel();

        $data = $modelUser->where('full_name', $username)->first();

        // cek password old untuk dapat melanjutkan update profil
        if ($data['password'] == password_hash($this->request->getVar('oldPassword'), PASSWORD_DEFAULT)) {
            echo 'sesuai';
        }

        $passwordLamaSesuai = password_verify($this->request->getVar('oldPassword'), $data['password']);
        // password tidak sesuai
        if (!$passwordLamaSesuai) {
            session()->setFlashdata('alert', 'alert-danger');
            session()->setFlashdata('message', "Gagal melakukan update karena password lama yang anda masukkan salah.");

            return redirect()->to(base_url('/admin/profil'));
        }

        // periksa apakah melakukan update password juga, jika new password dan confirm sesuai maka update
        $newPassword  = $this->request->getVar('newPassword');
        $confirmPassword  = $this->request->getVar('confirmPassword');
        if (strlen($newPassword) > 5 && strlen($confirmPassword) > 5) {
            // periksa sesuai tidak password baru dengan konfirmasinya
            if ($this->request->getPost('newPassword') != $this->request->getPost('confirmPassword')) {
                session()->setFlashdata('alert', 'alert-danger');
                session()->setFlashdata('message', "Gagal melakukan update karena password baru dan konfirmasi password tidak sesuai.");

                return redirect()->to(base_url('/admin/profil'));
            }

            // jika sesuai maka update
            $modelUser->update($data['user_id'], [
                'full_name'     => $this->request->getPost('username'),
                'email'         => $this->request->getPost('email'),
                'password'      => password_hash($this->request->getVar('confirmPassword'), PASSWORD_DEFAULT)
            ]);

            session()->set([
                'username' => $this->request->getPost('username'),
                'email'    => $this->request->getPost('email')
            ]);


            session()->setFlashdata('alert', 'alert-success');
            session()->setFlashdata('message', "Berhasil melakukan update profil dan mengganti password.");
        } else if (empty($newPassword) && empty($confirmPassword)) {
            $modelUser->update($data['user_id'], [
                'full_name'     => $this->request->getPost('username'),
                'email'         => $this->request->getPost('email'),
            ]);

            session()->set([
                'username' => $this->request->getPost('username'),
                'email'    => $this->request->getPost('email')
            ]);

            session()->setFlashdata('alert', 'alert-success');
            session()->setFlashdata('message', "Berhasil melakukan update profil.");
        } else {
            session()->setFlashdata('alert', 'alert-danger');
            session()->setFlashdata('message', "Panjang password baru harus lebih dari 5 karakter.");
        }

        return redirect()->to(base_url('/admin/profil'));
    }
}
