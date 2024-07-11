<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\HakPilihModel;
use App\Models\KandidatModel;
use App\Models\MediaModel;
use App\Models\PesertaModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class KandidatController extends BaseController
{
    protected $kandidatModel;

    public function __construct()
    {
        $this->kandidatModel = new KandidatModel();
    }

    public function tambah_kandidat($slug)
    {
        $modelMediaPemilihan = new MediaModel();
        $media = $modelMediaPemilihan->getMediaBySlug($slug);

        $data = [
            'title'     => "Tambah Kandidat - " . ucwords($media['judul_pemilihan']) . ' | Admin E-Memilih',
            'media'     => $media
        ];

        return view('media/kandidat/tambah_kandidat', $data);
    }

    private function _kandidatRules($media_id = null): array
    {
        return [
            'nomor_urut' => [
                'rules' => "required|integer|greater_than[0]|" . ($media_id ? "unique_nomor_urut[nomor_urut,media_pemilihan_id,{$media_id}]" : "is_unique[kandidat.nomor_urut]"),
                'errors' => [
                    'required'      => "Kolom Nomer Urut wajib diisi.",
                    'greater_than'  => "Nomor Urut harus lebih besar dari 0",
                    'unique_nomor_urut'     => "Nomor Urut sudah digunakan"
                ]
            ],
            'nama_kandidat' => [
                'rules' => "required",
                'errors' => [
                    'required'  => "Kolom Nama Kandidat (Ketua/Pemimpin) wajib diisi."
                ]
            ],
            'poster'            => [
                'rules' => "is_image[poster]|max_size[poster,2024]|mime_in[poster,image/png,image/jpeg,image/jpg]",
                'errors' => [
                    'is_image'  => "File Poster harus gambar",
                    'max_size'  => "Ukuran Poster tidak boleh lebih dari 2 Mb",
                    'mime_in'   => "Hanya Poster dengan format png, jpg, dan jpeg yang dibolehkan"
                ]
            ],
            'visi' => [
                'rules' => "required",
                'errors' => [
                    'required'  => "Kolom Visi wajib diisi."
                ]
            ],
            'misi' => [
                'rules' => "required",
                'errors' => [
                    'required'  => "Kolom Misi wajib diisi."
                ]
            ],
        ];
    }

    //aksi untuk menambahkan kandidat baru
    public function aksi_tambah($slug)
    {
        // ambil data media pelatihan
        $modelMediaPemilihan = new MediaModel();
        $media = $modelMediaPemilihan->getMediaBySlug($slug);

        $validation = Services::validation();
        $validation->setRules($this->_kandidatRules($media['media_id']));

        if (!$validation->run($this->request->getPost())) {
            return redirect()->to(base_url('/admin/media_pemilihan/') . $media['slug'] . '/tambah_kandidat')->withInput()->with('errors', $validation->getErrors());
        }

        // ambil poster
        $filePoster = $this->request->getFile('poster');
        // cek poster
        if ($filePoster->getError() == 4) {
            $posterName = 'default-photo-kandidat.jpg';
        } else {
            // lakukan pembuatan nama file random
            $posterName = $filePoster->getRandomName();

            // pindahkan simpan file ke directori
            $filePoster->move('img/kandidat', $posterName);
        }

        // untuk menggabungkan inputan nama ketua dan wakil menjadi 1 data dengan dipisah koma 
        $nama_kandidat = "";
        if ($this->request->getPost('nama_wakil_kandidat') != "") {
            $nama_kandidat = $this->request->getPost('nama_kandidat') . "," . $this->request->getPost('nama_wakil_kandidat');
        } else {
            $nama_kandidat = $this->request->getPost('nama_kandidat');
        }

        $data = [
            'media_pemilihan_id'    => $media['media_id'],
            'nomor_urut'            => $this->request->getPost('nomor_urut'),
            'nama_kandidat'         => $nama_kandidat,
            'visi'                  => $this->request->getPost('visi'),
            'misi'                  => $this->request->getPost('misi'),
            'poster'                => $posterName,
        ];

        $this->kandidatModel->save($data);

        session()->setFlashdata('pesan', "<b>Berhasil!</b>, Kandidat baru telah ditambahkan.");
        session()->setFlashdata('alert', "alert-success");

        return redirect()->to(base_url('/admin/media_pemilihan/detail/') . $media['slug']);
    }

    public function edit_kandidat($slug, $kandidat_id)
    {
        $mediaModel = new MediaModel();
        $media = $mediaModel->getMediaBySlug($slug);

        if (is_null($media)) {
            return view('404');
        }

        $dataKandidat = $this->kandidatModel->getDataKandidatByIdAndMediaId($kandidat_id, $media['media_id']);
        // ini digunakan untuk keperluan melakukan perubahan pada data wakil kandidat
        $pecahNamaKandidat = explode(',', $dataKandidat['nama_kandidat']);
        if (count($pecahNamaKandidat) != 1) {
            $wakil = $pecahNamaKandidat[1];
        } else {
            $wakil = "";
        }

        $data = [
            'title'     => ucwords($media['judul_pemilihan']) . ' | Edit Kandidat | Admin E-Memilih',
            'kandidat'  => $dataKandidat,
            'media'     => $media,
            'pecahNamaKandidat' => $pecahNamaKandidat,
            'wakil'     => $wakil
        ];

        return view('media/kandidat/edit_kandidat', $data);
    }

    public function aksi_edit($slug, $kandidat_id)
    {
        //ambil data kandidat berdasarkan id kandidat
        $kandidat = $this->kandidatModel->getDataKandidatById($kandidat_id);

        // ambil data media berdasarkan kandidat masuk pada media pemilihan dengan id berapa
        $mediaModel = new MediaModel();
        $media = $mediaModel->getMediaBySlug($slug);

        $validation = Services::validation();
        $validation->setRules($this->_kandidatRules($media['media_id']));

        //unset untuk rule is_unique/unique_nomor_urut jika nomor_urut saat ini yang dimasukkan masih sama tidak ada perubahan
        if ($kandidat['nomor_urut'] == $this->request->getPost('nomor_urut')) {
            $validation->setRules(["nomor_urut" => [
                'rules' => "required|integer|greater_than[0]",
                'errors' => [
                    'required'      => "Kolom Nomer Urut wajib diisi.",
                    'greater_than'  => "Nomor Urut harus lebih besar dari 0",
                ]
            ]]);
        }

        if (!$validation->run($this->request->getPost())) {
            return redirect()->to(base_url('/admin/media_pemilihan/') . $media['slug'] . '/edit_kandidat/' . $kandidat['kandidat_id'])->withInput()->with('errors', $validation->getErrors());
        }

        // ambil data poster
        $filePoster = $this->request->getFile('poster');
        $oldFilePoster = $this->request->getPost('oldPoster');
        // cek poster
        if ($filePoster->getError() == 4) {
            $posterName = $oldFilePoster;
        } else {
            // lakukan pembuatan nama file random
            $posterName = $filePoster->getRandomName();

            // pindahkan simpan file ke directori
            $filePoster->move('img/kandidat', $posterName);

            // pengecekan apakah poster menggunakan foto default
            if ($oldFilePoster != 'default-photo-kandidat.jpg') {
                // cek apaka ada foto lama didirektori
                $filePath = "img/kandidat/" . $oldFilePoster;
                if (file_exists($filePath)) {
                    // hapus jika ada
                    unlink($filePath);
                }
            }
        }

        // penanganan nama kandidat
        $nama_kandidat = "";
        if ($this->request->getPost('nama_wakil_kandidat') != "") {
            $nama_kandidat = $this->request->getPost('nama_kandidat') . "," . $this->request->getPost('nama_wakil_kandidat');
        } else {
            $nama_kandidat = $this->request->getPost('nama_kandidat');
        }

        //data
        $data = [
            'nomor_urut'        => $this->request->getPost('nomor_urut'),
            'nama_kandidat'     => $nama_kandidat,
            'visi'              => $this->request->getPost('visi'),
            'misi'              => $this->request->getPost('misi'),
            'poster'            => $posterName,
            'media_pemilihan_id' => $media['media_id']
        ];

        // lakukan update
        $this->kandidatModel->update($kandidat_id, $data);

        session()->setFlashdata('pesan', '<b>Diperbarui!</b>, Data kandidat telah diubah.');
        session()->setFlashdata('alert', 'alert-warning');

        return redirect()->to(base_url('admin/media_pemilihan/detail/') . $slug);
    }

    public function remove($slug, $kandidat_id)
    {
        try {
            $modalMediaPemilihan = new MediaModel();
            $media = $modalMediaPemilihan->getMediaBySlug($slug);

            // !TODO Lakukan hapus suara untuk setiap peserta ke kandidat dengan id ini
            // hapus peserta pada tabel hak pilih berdasarkan kandidat_id
            $modelHakPilih = new HakPilihModel();
            $modelHakPilih->where(['media_pemilihan_id' => $media['media_id'], 'kandidat_id' => $kandidat_id])->delete();

            // ambil data kandidat
            $kandidat = $this->kandidatModel->getDataKandidatById($kandidat_id);
            //hapus data kandidat
            $this->kandidatModel->where(['media_pemilihan_id' => $media['media_id'], 'kandidat_id' => $kandidat_id])->delete();
            // pengecekan apakah poster menggunakan foto default
            if ($kandidat['poster'] != 'default-photo-kandidat.jpg') {
                // cek apaka ada foto lama didirektori
                $filePath = "img/kandidat/" . $kandidat['poster'];
                if (file_exists($filePath)) {
                    // hapus jika ada
                    unlink($filePath);
                }
            }

            $kandidatNama = explode(',', $kandidat['nama_kandidat']);
            if (count($kandidatNama) > 1) {
                session()->setFlashdata('pesan', '<b>Dihapus!</b>, kandidat ' . $kandidatNama[0] . ' & ' . $kandidatNama[1] . ' telah dihapus.');
            } else {
                session()->setFlashdata('pesan', '<b>Dihapus!</b>, kandidat ' . $kandidatNama[0] . ' telah dihapus.');
            }

            session()->setFlashdata('alert', 'alert-danger');

            return redirect()->to(base_url('admin/media_pemilihan/detail/') . $slug);
        } catch (DatabaseException $e) {
            // Tangani kesalahan database, termasuk foreign key constraint
            if (strpos($e->getMessage(), 'Cannot delete or update a parent row') !== false) {
                session()->setFlashdata('pesan', 'Data ini tidak dapat dihapus karena masih ada data yang terkait.');
            } else {
                session()->setFlashdata('pesan', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            }
            session()->setFlashdata('alert', 'alert-danger');

            return redirect()->to(base_url('/admin/media_pemilihan/detail/' . $slug));
        }
    }
}
