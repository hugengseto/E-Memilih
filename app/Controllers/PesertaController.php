<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\HakPilihModel;
use App\Models\MediaModel;
use App\Models\PesertaModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use DateTime;

class PesertaController extends BaseController
{
    protected $pesertaModel;

    public function __construct()
    {
        $this->pesertaModel = new PesertaModel();
    }

    public function tambah_peserta($slug)
    {
        // ambil data media pemilihan
        $modelMediaPemilihan = new MediaModel();
        $media = $modelMediaPemilihan->getMediaBySlug($slug);

        $data = [
            'title'         => $media['slug'] . "| Tambah Peserta | Admin E-Memilih",
            'media'         => $media
        ];

        return view('media/peserta/tambah_peserta', $data);
    }

    private function _rulesPeserta($media_id = null, $action = "withUniqueNoWhatsapp")
    {
        if ($action == 'withUniqueNoWhatsapp') {
            $nomor_whatsapp =  [
                'rules' => "required|integer|greater_than[0]|numeric|min_length[10]|max_length[14]|" . ($media_id ? "unique_nomor_whatsapp[nomor_whatsapp,media_pemilihan_id,{$media_id}]" : "is_unique[peserta_pemilihan.nomor_whatsapp]"),
                'errors' => [
                    'required'              => "Kolom Nomer Whatsapp wajib diisi.",
                    'greater_than'          => "Nomor Whatsapp harus lebih besar dari 0",
                    'numeric'               => "Kolom Nomor Whatsapp hanya menerima inputan angka",
                    'min_length'            => "Kolom Nomor Whatsapp minimal 10 karakter",
                    'max_length'            => "Kolom Nomor Whatsapp maksimal 10 karakter",
                    'unique_nomor_whatsapp' => "Nomor Whatsapp sudah digunakan"
                ],
            ];
        } else if ($action = 'withOutUniqueNomorWhatsapp') {
            $nomor_whatsapp =  [
                'rules' => "required|integer|greater_than[0]|numeric|min_length[10]|max_length[14]|",
                'errors' => [
                    'required'              => "Kolom Nomer Whatsapp wajib diisi.",
                    'greater_than'          => "Nomor Whatsapp harus lebih besar dari 0",
                    'numeric'               => "Kolom Nomor Whatsapp hanya menerima inputan angka",
                    'min_length'            => "Kolom Nomor Whatsapp minimal 10 karakter",
                    'max_length'            => "Kolom Nomor Whatsapp maksimal 10 karakter"
                ]
            ];
        }

        return [
            'nama_lengkap' => [
                'rules' => "required",
                'errors' => [
                    'required'  => "Kolom Nama Peserta wajib diisi."
                ]
            ],
            'tanggal_lahir' => [
                'rules' => "required",
                'errors' => [
                    'required'  => "Kolom Tanggal Lahir wajib diisi."
                ]
            ],
            'jenis_kelamin' => [
                'rules' => "required|in_list[Laki-Laki, Perempuan]",
                'errors' => [
                    'required'  => "Kolom Nama Kandidat (Ketua/Pemimpin) wajib diisi.",
                    'in_list'  => "Kolom Jenis Kelamin harus diisi dengan 'laki-laki' atau 'perempuan'."
                ]
            ],
            'nomor_whatsapp' => $nomor_whatsapp
        ];
    }

    public function aksi_tambah($slug)
    {
        // ambil data media pemilihan
        $modelMediaPemilihan = new MediaModel();
        $media = $modelMediaPemilihan->getMediaBySlug($slug);

        $validation = Services::validation();
        $validation->setRules($this->_rulesPeserta($media['media_id']));

        if (!$validation->run($this->request->getPost())) {
            return redirect()->to(base_url('/admin/media_pemilihan/') . $slug . '/tambah_peserta')->withInput()->with('errors', $validation->getErrors());
        }

        // ubah data tanggal lahir dengan format Y-m-d sesuai format database
        $formatTanggal = DateTime::createFromFormat('m/d/Y', $this->request->getVar('tanggal_lahir'));
        $tanggal_lahir = $formatTanggal->format('Y-m-d');

        $data = [
            'nama_lengkap'      => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin'     => $this->request->getPost('jenis_kelamin'),
            'nomor_whatsapp'    => $this->request->getPost('nomor_whatsapp'),
            'tanggal_lahir'     => $tanggal_lahir,
            'media_pemilihan_id' => $media['media_id'],
        ];

        // simpan
        $this->pesertaModel->save($data);

        session()->setFlashdata('pesan', "<b>Berhasil!</b>, peserta baru ditambahkan.");
        session()->setFlashdata('alert', 'alert-success');

        return redirect()->to('/admin/media_pemilihan/detail/' . $slug);
    }

    public function edit_peserta($slug, $peserta_id)
    {
        //ambil media pemilihan
        $modelMediaPemilihan = new MediaModel();
        $media = $modelMediaPemilihan->getMediaBySlug($slug);
        // ambil data peserta
        $peserta = $this->pesertaModel->getDataPesertaByIdAndMediaId($peserta_id, $media['media_id']);

        $data = [
            'title'         => $media['judul_pemilihan'] . ' | Edit Peserta | Admin E-Memilih',
            'media'         => $media,
            'peserta'       => $peserta
        ];

        return view('media/peserta/edit_peserta', $data);
    }

    public function aksi_edit($slug, $peserta_id)
    {
        // ambil media pemilihan
        $modalMediaPemilihan = new MediaModel();
        $media = $modalMediaPemilihan->getMediaBySlug($slug);
        // ambil data peserta
        $peserta = $this->pesertaModel->getDataPesertaById($peserta_id);

        $validation = Services::validation();
        $validation->setRules($this->_rulesPeserta($media['media_id']));

        // antisipasi duplicate nomor whatsapp yang sama dalam satu media pemilihan
        if ($peserta['nomor_whatsapp'] == $this->request->getPost('nomor_whatsapp')) {
            $validation->setRules($this->_rulesPeserta($media['media_id'], 'withOutUniqueNomorWhatsapp'));
        }

        if (!$validation->run($this->request->getPost())) {
            return redirect()->to(base_url('/admin/media_pemilihan/') . $slug . '/edit_peserta/' . $peserta_id)->withInput()->with('errors', $validation->getErrors());
        }

        // ubah data tanggal lahir dengan format Y-m-d sesuai format database
        $formatTanggal = DateTime::createFromFormat('m/d/Y', $this->request->getVar('tanggal_lahir'));
        $tanggal_lahir = $formatTanggal->format('Y-m-d');

        $data = [
            'nama_lengkap'      => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin'     => $this->request->getPost('jenis_kelamin'),
            'nomor_whatsapp'    => $this->request->getPost('nomor_whatsapp'),
            'tanggal_lahir'     => $tanggal_lahir,
            'media_pemilihan_id' => $media['media_id']
        ];

        // update
        $this->pesertaModel->update($peserta_id, $data);

        session()->setFlashdata('pesan', '<b>Diperbarui!</b>, data peserta berhasil diubah');
        session()->setFlashdata('alert', 'alert-warning');

        return redirect()->to(base_url('admin/media_pemilihan/detail/') . $slug);
    }

    public function  upload_csv($slug)
    {
        $modelMediaPemilihan = new MediaModel();
        $media = $modelMediaPemilihan->getMediaBySlug($slug);

        $data = [
            'title'         => ucwords($media['judul_pemilihan']) . ' | Import CSV (peserta) | Admin E-Memilih',
            'media'         => $media
        ];

        return view('media/peserta/upload_csv', $data);
    }

    public function aksi_upload_csv($slug)
    {
        // ambil data slug
        $modalMediaPemilihan = new MediaModel();
        $media = $modalMediaPemilihan->getMediaBySlug($slug);

        // Disable output buffering
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Headers for JSON response
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');

        $fileCsv = $this->request->getFile('fileCsv');

        if ($fileCsv->isValid() && !$fileCsv->hasMoved()) {
            $newName = $fileCsv->getRandomName();
            $fileCsv->move('csv', $newName);
            $filePath = 'csv/' . $newName;

            if (($handle = fopen($filePath, 'r')) !== FALSE) {
                $data = [];
                $isFirstRow = true;

                while (($row = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    if ($isFirstRow) {
                        $isFirstRow = false;
                        continue;
                    }

                    if (count($row) >= 5) {
                        $data[] = [
                            'no' => $row[0],
                            'nama_lengkap' => $row[1],
                            'jenis_kelamin' => $row[2],
                            'nomor_whatsapp' => $row[3],
                            'tanggal_lahir' => $row[4],
                            'media_pemilihan_id' => $media['media_id']
                        ];
                    }
                }

                fclose($handle);
                unlink($filePath);

                // Check if $data is empty before encoding
                if (empty($data)) {
                    http_response_code(400);
                    echo json_encode(['error' => 'No valid data found']);
                } else {
                    $response = json_encode($data);


                    // remove DEBUG comments from view
                    $response = preg_replace('#<!--.*-->[\r\n]#', '', $response);

                    $this->response->setHeader('content-type', 'text/plain;charset=UTF-8');
                    return $response;
                }
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'File cannot be opened']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid file or already moved']);
        }
    }

    // untuk melakukan penyimpanan ke dalam database
    public function aksi_import($slug)
    {
        // ambil data slug
        $modalMediaPemilihan = new MediaModel();
        $media = $modalMediaPemilihan->getMediaBySlug($slug);

        $json = $this->request->getJSON(); // Ambil data JSON dari request
        $getData = json_decode(json_encode($json), true); // Ubah menjadi array asosiatif

        // Lakukan operasi yang diperlukan, misalnya menyimpan ke dalam database
        // Contoh: Simpan data ke dalam database
        $jumlahDiImport = 0;
        $jumlahGagalImport = 0;

        foreach ($getData as $row) {
            // melakukan perubahan value jenis kelamin
            $jenis_kelamin = "";
            if ($row['jenis_kelamin'] == 'L') {
                $jenis_kelamin = "Laki-Laki";
            } else if ($row['jenis_kelamin'] == 'P') {
                $jenis_kelamin = "Perempuan";
            }

            $data = [
                'nama_lengkap' => $row['nama_lengkap'],
                'jenis_kelamin' => $jenis_kelamin,
                'nomor_whatsapp' => $row['nomor_whatsapp'],
                'tanggal_lahir' => $row['tanggal_lahir'],
                'media_pemilihan_id' => $row['media_pemilihan_id']
            ];

            // cek apakah data sudah ada dengan no whatsapp yang sama
            $cek = $this->pesertaModel->getDataPesertaByNoWhatsappAndMediaId($row['nomor_whatsapp'], $media['media_id']);
            // jika nomor whatsapp dan media pemilihan berbeda maka insert ke database
            if (is_null($cek)) {
                $this->pesertaModel->insert($data);
                $jumlahDiImport += 1;
            } else {
                $jumlahGagalImport += 1;
                // bila tidak tidak terjadi apa-apa alias tidak ditambahkan
            }
        }


        $total_data = count($getData);

        // Kirim respons ke client
        return $this->response->setJSON(['message' => 'BERHASIL!, Import data peserta sukses (' . $jumlahDiImport . ') dan gagal diimport (' . $jumlahGagalImport . '). total keseluruhan yang coba diimport ' . $total_data . ' data ||DATA DENGAN NOMOR WHATSAPP YANG SUDAH TERDAFTAR AKAN GAGAL DIIMPORT||']);
    }

    public function remove($slug, $peserta_id)
    {
        try {
            $modalMediaPemilihan = new MediaModel();
            $media = $modalMediaPemilihan->getMediaBySlug($slug);
            $peserta = $this->pesertaModel->getDataPesertaById($peserta_id);

            $this->pesertaModel->where(['media_pemilihan_id' => $media['media_id'], 'peserta_id' => $peserta_id])->delete();

            session()->setFlashdata('pesan', '<b>Dihapus!</b>, Peserta <i>' . $peserta['nama_lengkap'] . ' - ' . $peserta['nomor_whatsapp'] . '</i> telah diapus dari database');
            session()->setFlashdata('alert', 'alert-danger');

            return redirect()->to('/admin/media_pemilihan/detail/' . $slug);
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

    public function remove_all($slug)
    {
        try {
            $modalMediaPemilihan = new MediaModel();
            $modelHakPilih      = new HakPilihModel();

            $media = $modalMediaPemilihan->getMediaBySlug($slug);

            $keyKonfirmasi = trim("Hapus " . ucwords($media['judul_pemilihan']));

            //cek konfirmasi kalimat sesuai atau tidak jika iya hapus seluruh data peserta
            if ($this->request->getPost('konfirmasi') == $keyKonfirmasi) {
                // hapus data hak suara
                $modelHakPilih->where(['media_pemilihan_id' => $media['media_id']])->delete();

                // query builder untuk hapus
                $this->pesertaModel->where(['media_pemilihan_id' => $media['media_id']])->delete();

                session()->setFlashdata('pesan', "<b>Berhasil!</b>, Seluruh data peserta <b>" . ucwords($media['judul_pemilihan']) . "</b> telah berhasil dihapus.");
                session()->setFlashdata('alert', 'alert-danger');
            } else {
                session()->setFlashdata('pesan', "<b>Gagal!</b>, Pengahapusan seluruh data peserta <b>" . ucwords($media['judul_pemilihan']) . "</b>, konfirmasi tidak sesuai.");
                session()->setFlashdata('alert', 'alert-info');
            }

            return redirect()->to(('/admin/media_pemilihan/detail/' . $slug));
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
