<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\HakPilihModel;
use App\Models\KandidatModel;
use App\Models\MediaModel;
use App\Models\PesertaModel;
use CodeIgniter\Config\Services;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;

class MediaController extends BaseController
{
    protected $modelMediaPemilihan;
    protected $modelKandidat;
    protected $modelPeserta;
    protected $modelHakPilih;

    public function __construct()
    {
        $this->modelMediaPemilihan = new MediaModel();
        $this->modelKandidat = new KandidatModel();
        $this->modelPeserta = new PesertaModel();
        $this->modelHakPilih = new HakPilihModel();
    }

    public function index()
    {
        $data = [
            'title' => "Media Pemilihan | Admin E-Memilih",
            'media' => $this->modelMediaPemilihan->getAllDataByNewData(),
        ];

        return view('media/index', $data);
    }

    // untuk memuat halaman tambah data media pemilihan
    public function tambah()
    {

        $data = [
            'title' => "Tambah Media Pemilihan | Admin E-Memilih"
        ];

        return view('media/tambah', $data);
    }

    private function _rules($media_id = null): array
    {
        return [
            'judul_pemilihan'    => [
                'rules' => "required|max_length[70]|" . ($media_id ? "is_unique[media_pemilihan.judul_pemilihan, media_id , {$media_id}]" : "is_unique[media_pemilihan.judul_pemilihan]"),
                'errors' => [
                    'required' => "Judul Pemilihan harus diisi.",
                    'max_length' => "Panjang Judul Pemilihan maksimal 70 karakter.",
                    'is_unique' => "Judul Pemilihan sudah digunakan.",
                ]
            ],
            'pelaksanaan'       => [
                'rules' => "required",
                'errors' => [
                    'required' => "Tanggal Waktu Pelaksanaan harus diisi."
                ]
            ],
            'kata_kunci' => [
                'rules'         => "required|" . ($media_id ? "is_unique[media_pemilihan.kata_kunci, media_id , {$media_id}]" : "is_unique[media_pemilihan.kata_kunci]"),
                'errors' => [
                    'required'  => "Kata Kunci harus diisi.",
                    'is_unique' => "Kata Kunci sudah digunakan.",
                    'max_length' => "Panjang Kata Kunci maksimal 15 karakter."
                ]
            ],
            'poster'            => [
                'rules' => "is_image[poster]|max_size[poster,1024]|mime_in[poster,image/png,image/jpeg,image/jpg]",
                'errors' => [
                    'is_image'  => "File Poster harus gambar",
                    'max_size'  => "Ukuran Poster tidak boleh lebih dari 1 Mb",
                    'mime_in'   => "Hanya Poster dengan format png, jpg, dan jpeg yang dibolehkan"
                ]
            ],
        ];
    }

    // untuk memisahkan inputan waktu pelaksanaan
    private function _pisahWaktuPelaksanaan($pelaksanaan): array
    {
        // memisahkan string dengan menggunakan " - " sebagai delimiter
        list($mulai_pemilihan, $batas_pemilihan) = explode(' - ', $pelaksanaan);

        //mengembalikan dalam bentuk array dengan tanggal dan waktu yang dipisahkan
        return [
            'mulai_pemilihan' => date('Y-m-d H:i:s', strtotime($mulai_pemilihan)),
            'batas_pemilihan' => date('Y-m-d H:i:s', strtotime($batas_pemilihan)),
        ];
    }

    // aksi ketika menambah data baru media pemilihan
    public function aksi_tambah()
    {
        $validation = Services::validation();

        $validation->setRules($this->_rules());

        if (!$validation->run($this->request->getPost())) {
            return redirect()->to(base_url() . 'admin/media_pemilihan/tambah')->withInput()->with('errors', $validation->getErrors());
        }

        //melakukan pemecahan data waktu pelaksanaan untuk disimpan di database pada tabel mulai_pemilihan dan batas_pemilihan
        $pelaksanaan = $this->_pisahWaktuPelaksanaan($this->request->getPost('pelaksanaan'));

        // penangan untuk upload file 
        $fileFoto = $this->request->getFile('poster');
        // cek foto
        if ($fileFoto->getError() == 4) {
            $fotoName = 'vote.png';
        } else {
            // lakukan pembuatan nama file random
            $fotoName = $fileFoto->getRandomName();

            // pindahkan simpan file ke directori
            $fileFoto->move('img', $fotoName);
        }

        $data = [
            'judul_pemilihan'   => $this->request->getPost('judul_pemilihan'),
            'mulai_pemilihan'   => $pelaksanaan['mulai_pemilihan'],
            'batas_pemilihan'   => $pelaksanaan['batas_pemilihan'],
            'kata_kunci'        => $this->request->getPost('kata_kunci'),
            'poster'            => $fotoName,
            'slug'              => createSlug($this->request->getPost('judul_pemilihan'))
        ];

        // simpan data ke database
        $this->modelMediaPemilihan->save($data);

        session()->setFlashdata('message', '<strong>Berhasil!</strong> Media Pemilihan ditambahkan.');
        session()->setFlashdata('alert', 'alert-success');

        return redirect()->to(base_url('admin/media_pemilihan'));
    }

    // edit media pemilihan
    public function edit($slug): string
    {
        $media = $this->modelMediaPemilihan->getMediaBySlug($slug);

        // lakukan pengembalian format sesuai aturan range tanggal pelaksanaan
        $pelaksanaan = format_datetime_range($media['mulai_pemilihan'], $media['batas_pemilihan']);

        $data = [
            'title'         => ucwords($media['judul_pemilihan']) . " | Edit | Admin E-Memilih",
            'media'         => $media,
            'pelaksanaan'   => $pelaksanaan
        ];

        return view('media/edit', $data);
    }

    // aksi edit media pemilihan
    public function aksi_edit($slug)
    {
        //ambil detail data berdasarkan slug
        $media = $this->modelMediaPemilihan->getMediaBySlug($slug);

        $validation = Services::validation();
        $validation->setRules($this->_rules($media['media_id']));


        if (!$validation->run($this->request->getPost())) {
            return redirect()->to(base_url() . '/admin/media_pemilihan/edit/' . $slug)->withInput()->with('errors', $validation->getErrors());
        }

        //melakukan pemecahan data waktu pelaksanaan untuk disimpan di database pada tabel mulai_pemilihan dan batas_pemilihan
        $pelaksanaan = $this->_pisahWaktuPelaksanaan($this->request->getPost('pelaksanaan'));

        // penangan untuk upload file 
        // ambil foto
        $fileFoto = $this->request->getFile('poster');
        $fileFotoLama = $this->request->getPost('posterLama');
        // cek foto
        if ($fileFoto->getError() == 4) {
            $fotoName = $fileFotoLama;
        } else {
            // lakukan pembuatan nama file random
            $fotoName = $fileFoto->getRandomName();

            // pindahkan simpan file ke directori
            $fileFoto->move('img', $fotoName);

            // pengecekan apakah poster menggunakan foto default
            if ($fileFotoLama != 'vote.png') {
                // cek apaka ada foto lama didirektori
                $filePath = "img/" . $fileFotoLama;
                if (file_exists($filePath)) {
                    // hapus jika ada
                    unlink($filePath);
                }
            }
        }

        $data = [
            'judul_pemilihan'   => $this->request->getPost('judul_pemilihan'),
            'mulai_pemilihan'   => $pelaksanaan['mulai_pemilihan'],
            'batas_pemilihan'   => $pelaksanaan['batas_pemilihan'],
            'kata_kunci'        => $this->request->getPost('kata_kunci'),
            'poster'            => $fotoName,
            'slug'              => createSlug($this->request->getPost('judul_pemilihan'))
        ];

        // simpan data ke database
        $this->modelMediaPemilihan->update($media['media_id'], $data);

        session()->setFlashdata('message', '<strong>Diubah!</strong> Media Pemilihan berhasil diperbarui.');
        session()->setFlashdata('alert', 'alert-warning');

        return redirect()->to(base_url('admin/media_pemilihan'));
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

    /**
     * Fungsi untuk menghitung persentase perolehan suara
     * 
     * @param array $suaraKandidat Array yang berisi jumlah suara yang diperoleh oleh masing-masing kandidat
     * @param int $totalSuara Total jumlah suara
     * @return array Array yang berisi persentase perolehan suara untuk masing-masing kandidat
     */
    private function _hitungPersentaseSuara($suaraKandidat, $totalSuara)
    {
        $persentaseSuara = [];

        foreach ($suaraKandidat as $kandidat => $suara) {
            if ($totalSuara > 0) {
                $persentaseSuara[$kandidat] = ($suara / $totalSuara) * 100;
            } else {
                $persentaseSuara[$kandidat] = 0;
            }
        }

        return $persentaseSuara;
    }

    public function detail($slug)
    {

        $media = $this->modelMediaPemilihan->getMediaBySlug($slug);

        if (is_null($media)) {
            return view('404');
        }

        $kandidat = $this->modelKandidat->getDataKandidatByMediaId($media['media_id']);
        $peserta = $this->modelPeserta->getDataPesertaByMediaId($media['media_id']);

        $perolehanSuara = $this->modelHakPilih->getDataByMediaId($media['media_id']);

        // menentukan daftar nama kandidat pada pie chart
        $daftarNamaKandidat = [];
        foreach ($kandidat as $row) {
            $daftarNamaKandidat[] = '"' . $row['nama_kandidat'] . '"';
        }

        // menentukan jumlah perolehan setiap kandidat
        // Inisialisasi array untuk jumlah perolehan suara setiap kandidat
        $jumlahSuaraKandidat = [];

        // Inisialisasi jumlah suara untuk setiap kandidat dengan nilai 0
        foreach ($kandidat as $row) {
            $jumlahSuaraKandidat[$row['kandidat_id']] = 0;
        }

        // Iterasi melalui perolehan suara
        foreach ($perolehanSuara as $vote) {
            // Jika kandidat_id ada dalam jumlahSuaraKandidat, tambahkan jumlah suaranya
            if (isset($jumlahSuaraKandidat[$vote['kandidat_id']])) {
                $jumlahSuaraKandidat[$vote['kandidat_id']]++;
            }
        }

        // Tambahkan nilai tidak memilih ke array jumlah suara
        $jumlahSuaraKandidat['tidak_memilih'] = (count($peserta) - count($perolehanSuara));

        // Mengambil hanya nilai jumlah suara dari array asosiatif
        $jumlahSuara = array_values($jumlahSuaraKandidat);

        // untuk dikirimkan di view 
        $suaraKandidat = $jumlahSuara;

        $totalSuara = array_sum($suaraKandidat);

        $persentaseSuara = $this->_hitungPersentaseSuara($suaraKandidat, $totalSuara);

        // Menampilkan hasil untuk debugging
        // Menghapus elemen dengan kunci 'tidak_memilih'
        unset($jumlahSuaraKandidat['tidak_memilih']);

        $keyTertinggi = null;
        if (!empty($jumlahSuaraKandidat)) {
            // Mencari nilai tertinggi dari array yang tersisa
            $maxSuara = max($jumlahSuaraKandidat);

            // Mendapatkan kunci yang memiliki nilai tertinggi (mendapatkan kandidat_id)
            $keyTertinggi = array_search($maxSuara, $jumlahSuaraKandidat);
        }

        // Menggabungkan array menjadi string dengan pemisah koma dan spasi
        $stringPresentasiSuara = implode(',', $persentaseSuara);
        $stringNamaKandidat = implode(', ', $daftarNamaKandidat);

        $hasilPemilihan = [
            'daftar_kandidat' => $stringNamaKandidat . ", " . '"' . "Tidak Memilih" . '"', //memformat untuk kebutuhan dijavascriptnya
            'jumlah_pemilih' => count($peserta),
            'sudah_memilih' => count($perolehanSuara),
            'tidak_memilih' => (count($peserta) - count($perolehanSuara)),
            'perolehan_suara' => $stringPresentasiSuara,
        ];

        $data = [
            'title'         => ucwords($media['judul_pemilihan']) . " | Detail | Admin E-Memilih",
            'media'         => $media,
            'kandidat'      => $kandidat,
            'peserta'       => $peserta,
            't_kandidat'    => count($kandidat),
            't_peserta'     => count($peserta),
            'kandidat_terpilih' => $this->modelKandidat->getDataKandidatById($keyTertinggi),
            'status'        => function ($mulai, $batas) {
                return $this->_getStatus($mulai, $batas);
            },
            'hasil'         => $hasilPemilihan,
        ];

        return view('media/detail', $data);
    }

    public function remove($media_id)
    {
        try {
            $media = $this->modelMediaPemilihan->getMediaById($media_id);

            $keyKonfirmasi = trim("Hapus " . ucwords($media['judul_pemilihan']));

            //cek konfirmasi kalimat sesuai atau tidak jika iya hapus seluruh data peserta
            if ($this->request->getPost('konfirmasi') == $keyKonfirmasi) {
                // hapus seluruh hak pilih lebih dahulu
                $this->modelHakPilih->where('media_pemilihan_id', $media_id)->delete();
                // selanjutnya hapus seluruh data peserta 
                $this->modelPeserta->where('media_pemilihan_id', $media_id)->delete();;
                // selanjutnya hapus data kandidat
                $this->modelKandidat->where('media_pemilihan_id', $media_id)->delete();;
                // baru hapus data media pemilihannya
                $this->modelMediaPemilihan->where('media_id', $media_id)->delete();

                // pengecekan apakah poster menggunakan foto default
                if ($media['poster'] != 'vote.png') {
                    // cek apaka ada foto lama didirektori
                    $filePath = "img/" . $media['poster'];
                    if (file_exists($filePath)) {
                        // hapus jika ada
                        unlink($filePath);
                    }
                }

                session()->setFlashdata('alert', 'alert-danger');
                session()->setFlashdata('message', '<b>Dihapus!</b>, media pemilihan <b>' . ucwords($media['judul_pemilihan']) . '</b> telah dihapus secara permanen.');
            } else {
                session()->setFlashdata('message', "<b>Gagal!</b>, menghapus media pemilihan <b>" . ucwords($media['judul_pemilihan']) . "</b>, konfirmasi tidak sesuai.");
                session()->setFlashdata('alert', 'alert-info');
            }

            return redirect()->to(base_url('admin/media_pemilihan'));
        } catch (DatabaseException $e) {
            // Tangani kesalahan database, termasuk foreign key constraint
            if (strpos($e->getMessage(), 'Cannot delete or update a parent row') !== false) {
                session()->setFlashdata('message', 'Data ini tidak dapat dihapus karena masih ada data yang terkait.');
            } else {
                session()->setFlashdata('message', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            }
            session()->setFlashdata('alert', 'alert-danger');

            return redirect()->to(base_url('admin/media_pemilihan'));
        }
    }
}
