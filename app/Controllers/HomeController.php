<?php

namespace App\Controllers;

use App\Models\HakPilihModel;
use App\Models\KandidatModel;
use App\Models\MediaModel;
use App\Models\PesertaModel;
use CodeIgniter\Config\Services;
use DateTime;

class HomeController extends BaseController
{

    public function index(): string
    {
        //ambil data media pemilihan
        $modalMediaPemilihan = new MediaModel();
        $media = $modalMediaPemilihan->orderBy('created_at', 'Desc')->limit(5)->findAll();

        // Fungsi untuk menghitung selisih hari antara tanggal tertentu dan hari ini
        $daysDifferenceFromToday = function ($date) {
            $currentDate = new DateTime(); // Tanggal hari ini
            $givenDate = new DateTime($date); // Tanggal dari data

            // Hitung selisih hari antara $givenDate dan $currentDate
            $interval = $currentDate->diff($givenDate);
            $daysDiff = $interval->days;

            return $daysDiff; // Kembalikan selisih hari
        };

        // Fungsi untuk melakukan sorting berdasarkan rentang tanggal terdekat
        $sortByDateProximity = function ($a, $b) use ($daysDifferenceFromToday) {
            // Tentukan status untuk $a dan $b berdasarkan kriteria
            $statusA = $this->_getStatus($a['mulai_pemilihan'], $a['batas_pemilihan']);
            $statusB = $this->_getStatus($b['mulai_pemilihan'], $b['batas_pemilihan']);

            // Urutkan berdasarkan kriteria yang telah ditentukan
            if ($statusA == 'Berlangsung') {
                return -1; // $a lebih awal jika sedang berlangsung
            } elseif ($statusB == 'Berlangsung') {
                return 1; // $b lebih awal jika sedang berlangsung
            } elseif ($statusA == 'Mendatang' && $statusB != 'Berlangsung') {
                return -1; // $a lebih awal jika mendekati dan $b bukan sedang berlangsung
            } elseif ($statusB == 'Mendatang' && $statusA != 'Berlangsung') {
                return 1; // $b lebih awal jika mendekati dan $a bukan sedang berlangsung
            } elseif ($statusA == 'Selesai' && ($statusB == 'Mendatang' || $statusB == 'Berlangsung')) {
                return 1; // $b lebih awal jika $a terlewat dan $b mendekati atau sedang berlangsung
            } elseif ($statusB == 'Selesai' && ($statusA == 'Mendatang' || $statusA == 'Berlangsung')) {
                return -1; // $a lebih awal jika $b terlewat dan $a mendekati atau sedang berlangsung
            } else {
                // Jika kedua data memiliki status yang sama, urutkan berdasarkan kedekatan dengan hari ini
                $daysDiffA = $daysDifferenceFromToday($a['mulai_pemilihan']);
                $daysDiffB = $daysDifferenceFromToday($b['mulai_pemilihan']);
                return $daysDiffA - $daysDiffB;
            }
        };

        // Sortir data media berdasarkan rentang tanggal terdekat
        usort($media, $sortByDateProximity);


        $data = [
            'title' => 'Halaman Utama | E-Memilih',
            'media' => $media,
            'getStatus' =>  function ($mulai, $batas) {
                return $this->_getStatus($mulai, $batas);
            }
        ];


        return view('home/index', $data);
    }

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

    public function mediaPemilihan($slug)
    {
        // inisialisasi model
        $modalMedia = new MediaModel();
        $modelKandidat = new KandidatModel();
        $modelPeserta = new PesertaModel();
        $modelHakPilih = new HakPilihModel();
        // ambil data media pemilihan
        $media = $modalMedia->getMediaBySlug($slug);
        if (empty($media)) {
            return redirect()->to(base_url('/admin/media_pemilihan'));
        }
        $kandidat = $modelKandidat->getDataKandidatByMediaId($media['media_id']);
        $peserta = $modelPeserta->getDataPesertaByMediaId($media['media_id']);

        $perolehanSuara = $modelHakPilih->getDataByMediaId($media['media_id']);

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
        // dd($jumlahSuara);

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
            'title'     => ucwords($media['judul_pemilihan'] . " | E-Memilih"),
            'media'     => $media,
            'kandidat'  => $kandidat,
            'getStatus' => function ($mulai, $batas) {
                return $this->_getStatus($mulai, $batas);
            },
            'hasil'     => $hasilPemilihan
        ];

        return view('home/media_pemilihan', $data);
    }

    private function _generateUniqueOtp($hakPilihModal)
    {
        do {
            // Buat kode OTP secara acak
            $kodeOtp = rand(100000, 999999);

            // Cek apakah kode OTP sudah ada di database
            $cekKodeDiDb = $hakPilihModal->getOtpFromRandomOtp($kodeOtp);
        } while ($cekKodeDiDb); // Ulangi jika kode OTP sudah ada di database

        return $kodeOtp;
    }

    // fungsi mengatur permintaan kode OTP
    public function mintaOtp($slug, $kandidat_id)
    {
        $whatsapp = $this->request->getPost('nomor_whatsapp');

        // inisialisasi modal
        $mediaModal = new MediaModel();
        $pesertaModal    = new PesertaModel();
        $kandidatModal   = new KandidatModel();
        $hakPilihModal  = new HakPilihModel();

        // ambil data
        $media = $mediaModal->getMediaBySlug($slug);
        $kandidat = $kandidatModal->getDataKandidatById($kandidat_id);

        // validasi
        $validation = Services::validation();
        $validation->setRules([
            'nomor_whatsapp' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nomor Whatsapp tidak boleh kosong!.'
                ]
            ]
        ]);


        if (!$validation->run($this->request->getPost())) {
            return redirect()->to(base_url('/') . $slug)->withInput()->with('errors', $validation->getErrors());
        }

        // pengecekan apakah nomer whatsapp terdaftar sebagai peserta sesuai dengan media pemilihannya
        // jika data peserta ada dan media pemilihan sesuai
        $peserta = $pesertaModal->getDataPesertaByNoWhatsappAndMediaId($whatsapp, $media['media_id']);
        if ($peserta) {
            // cek apakah  data tabel hak pilih sudah ada dan status konfirmasi "sudah"
            $cekSudahMemilih = $hakPilihModal->getDataByStatusKonfirmasi($peserta['peserta_id'], "Sudah", $media['media_id']);

            if ($cekSudahMemilih) {
                return redirect()->to(base_url('/') . $slug)->with('errors', ['sudah_memilih' => "Anda hanya dapat memilih sekali disetiap pemilihan."]);
            } else {
                $dataHakPilih = $hakPilihModal->getDataByPesertaId($peserta['peserta_id']);

                // buat random kode
                $kodeOtp = $this->_generateUniqueOtp($hakPilihModal);
                // cek apakah data di database hak pilih sudah punya
                if ($dataHakPilih) {

                    // bila sudah punya
                    // update table hak pilih untuk mengupdate kode otp bila melakukan permintaan tidak hanya sekali
                    $hakPilihModal->update($dataHakPilih['hak_pilih_id'], [
                        'kandidat_id' => $kandidat['kandidat_id'],
                        'media_pemilihan_id' => $media['media_id'],
                        'otp' => $kodeOtp,
                        'status_konfirmasi' => 'Belum',
                    ]);

                    $ambilWaktuDibuatKode = $hakPilihModal->getDataByPesertaIdNMediaId($peserta['peserta_id'], $media['media_id']);

                    // kirim kode OTP ke Wa
                    $resultJson = $this->_sendOtpViaWa($peserta['nomor_whatsapp'], $kodeOtp, $ambilWaktuDibuatKode['updated_at'], $slug);

                    return redirect()->to(base_url('/') . $slug . '/' . $whatsapp)->with('status', ['status_pengiriman' => "Permintan kode OTP diproses, silahkan cek Whatsapp Anda."]);
                } else {
                    // bila belum ada datanya
                    // lakukan insert data di tabel hak pilih untuk membuat kode otp
                    $hakPilihModal->insert([
                        'peserta_id' => $peserta['peserta_id'],
                        'kandidat_id' => $kandidat['kandidat_id'],
                        'media_pemilihan_id' => $media['media_id'],
                        'otp'        => $kodeOtp,
                        'status_konfirmasi' => 'Belum'
                    ]);

                    $ambilWaktuDibuatKode = $hakPilihModal->getDataByPesertaIdNMediaId($peserta['peserta_id'], $media['media_id']);

                    // kirim kode ke WA
                    $resultJson = $this->_sendOtpViaWa($peserta['nomor_whatsapp'], $kodeOtp, $ambilWaktuDibuatKode['updated_at'], $slug);
                    // $decodeResultJson = json_decode($resultJson, true);

                    return redirect()->to(base_url('/') . $slug  . '/' . $whatsapp)->with('status', ['status_pengiriman' => "Permintan kode OTP diproses, silahkan cek Whatsapp Anda."]);
                }
            }
        } else {
            return redirect()->to(base_url('/') . $slug)->withInput()->with('errors', ['nomor_whatsapp' => "Nomer Whatsapp Anda tidak terdaftar dalam pemilihan ini."]);
        };
    }

    // fungsi untuk mengirimkan wa menggunakan https://fonnte.com/
    private function _sendOtpViaWa($target, $kodeOtp, $waktu, $slug)
    {
        $urlKonfirmasi = base_url('/') . $slug . '/' . $target;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => "Kode OTP serah hak pilih anda : *{$kodeOtp}*
Masukkan kode pada url ini : {$urlKonfirmasi}

_waktu permintaan {$waktu} (berlaku 7 menit saja)_",
                'countryCode' => '62', //optional
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: GQsjUfkN9oc#92q4B6r6' //change TOKEN to your actual token
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
            return $error_msg;
        }
        return $response;
    }

    public function konfirmasiPilihan($slug, $nomor_whatsapp)
    {
        // inisialisasi model
        $modalMediaPemilihan    = new MediaModel();
        $modelPeserta           = new PesertaModel();
        $modelHakPilih          = new HakPilihModel();

        // get data by slug
        $media = $modalMediaPemilihan->getMediaBySlug($slug);
        // get peserta by nomer wa dan media id
        $peserta = $modelPeserta->getDataPesertaByNoWhatsappAndMediaId($nomor_whatsapp, $media['media_id']);

        if (is_null($peserta)) {
            return redirect()->to(base_url('/') . $slug)->withInput()->with('errors', ['nomor_whatsapp' => "Nomor Whatsapp Anda tidak terdaftar pada pemilihan ini."]);
        }
        // get data hak pilih by peserta id dan media id
        $hakPilih = $modelHakPilih->getDataByPesertaIdNMediaId($peserta['peserta_id'], $media['media_id']);
        // get data hak pilih by hak pilih id
        if (is_null($hakPilih)) {
            return redirect()->to(base_url('/') . $slug)->withInput()->with('errors', ['nomor_whatsapp' => "Anda belum menentukan pilihan, silahkan melakukan vote lebih dulu."]);
        }

        $getDetailHakPilih = $modelHakPilih->getHakPilihById($hakPilih['hak_pilih_id']);
        // cek apakah sudah melakukan submit hak pilihnya dengan otp
        if ($getDetailHakPilih['status_konfirmasi'] == "Sudah") {
            return redirect()->to(base_url('/') . $slug)->with('status', ['sudah_memilih' => "Anda sudah melakukan submit hak pilih Anda pada pemilihan ini."]);
        }

        $data = [
            'title'     => 'Konfirmasi Pilihan | E-Memilih',
            'detail'    => $getDetailHakPilih,
            'peserta'   => $modelPeserta->getDataPesertaByNoWhatsappAndMediaId($nomor_whatsapp, $media['media_id'])
        ];

        return view('home/konfirmasi_otp', $data);
    }

    public function aksiKonfirmasiPilihan($slug, $hak_pilih_id)
    {
        $validation = Services::validation();
        $validation->setRules([
            'kodeOtp' => [
                'rules' => 'required|max_length[6]|min_length[6]',
                'errors' => [
                    'required' => 'Kode OTP tidak boleh kosong.',
                    'max_length' => 'Panjang kode tidak boleh lebih dari 6 karakter',
                    'min_length' => 'Panjang kode tidak boleh kurang dari 6 karakter'
                ]
            ]
        ]);

        // inisialisasi model
        $modelHakPilih = new HakPilihModel();

        $dataHakPilih = $modelHakPilih->getHakPilihById($hak_pilih_id);

        if (!$validation->run($this->request->getPost())) {
            return redirect()->to(base_url('/') . $slug . '/' . $dataHakPilih['nomor_whatsapp'])->withInput()->with('errors', $validation->getErrors());
        }

        // cek apakah kode otp tidak sesuai dengan data peserta yang melakukan permintaan kode otp
        if ($dataHakPilih['otp'] != $this->request->getPost('kodeOtp')) {
            return redirect()->to(base_url('/') . $slug . '/' . $dataHakPilih['nomor_whatsapp'])->withInput()->with('errors', ['otp' => 'Kode OTP tidak sesuai!.']);
        }

        // kode sesuai dengan permintaan yang dilakukan
        // dan cek apakah kode otp masih berlaku dan tidak lebih dari 7 menit
        $givenTime = new DateTime($dataHakPilih['updated_at']); //waktu yang dibandingkan 
        $currentTime = new DateTime(); //waktu saat ini
        $interval = $currentTime->diff($givenTime); //hitung selisih waktu
        // konversi selisih waktu ke menit
        $minutesDefference = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

        // tentukan apakah lebih dari 7 menit
        if ($minutesDefference > 7) {
            return redirect()->to(base_url('/') . $slug . '/' . $dataHakPilih['nomor_whatsapp'])->withInput()->with('errors', ['otp' => 'Kode OTP sudah kadaluwarsa!.']);
        }

        // lakukan update status konfirmasi dan kosongkan kode otp
        $modelHakPilih->update($hak_pilih_id, [
            'status_konfirmasi' => 'Sudah',
            'otp'               => '-'
        ]);

        return redirect()->to(base_url('/') . $dataHakPilih['slug'])->with('status', ['sukses' => 'Anda telah berhasil menyerahkan hak pilih anda. Terima kasih.']);
    }

    public function tentang()
    {
        $data = [
            'title'     => 'Tentang | E-Memilih'
        ];

        return view('home/tentang', $data);
    }
}
