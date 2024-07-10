$(function () {
  // Inisialisasi DataTable untuk example1
  $('#example1').DataTable({
    responsive: true, // Mengaktifkan mode responsif bawaan DataTables
    paging: true,
    searching: true,
    "columnDefs": [
            { "orderable": false, "targets": [2, 6] }//target kolom mana yang tidak diaktifkan pengurutannya
        ],
    info: true
  });

  // Inisialisasi DataTable untuk example2 dengan opsi kustom
  $('#example2').DataTable({
      paging: true,
      lengthChange: false,
      searching: false,
      ordering: true,
      info: true,
      autoWidth: false
  });

  $('#table-peserta').DataTable({
    responsive: true, // Mengaktifkan mode responsif bawaan DataTables
    paging: true,
    searching: true,
    "columnDefs": [
            { "orderable": false, "targets": [2, 5] }//target kolom mana yang tidak diaktifkan pengurutannya
        ],
    info: true
  });

  // Inisialisasi Date range picker with time picker
  $('#pelaksanaan').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
          format: 'MM/DD/YYYY hh:mm A'
      }
  });

  //Date picker
  $('#datepicker').datepicker({
    autoclose: true
  })

    //   ck edit
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('visi')
    CKEDITOR.replace('misi')

  // preview poster
  document.getElementById('poster').addEventListener('change', function(event) {
    var reader = new FileReader();

    reader.onload = function(e) {
        var img = document.getElementById('poster-preview');
        img.src = e.target.result;
    }

    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
});

});

// edit wakil
document.addEventListener('DOMContentLoaded', function(){
    const wakilCheckbox = document.getElementById('wakil_kandidat');
    const formWakilKandidat = document.getElementById('form_wakil_kandidat');
    const namaWakilKandidatInput = document.getElementById('nama_wakil_kandidat');

    // ! initialNamaWakil ada di file _footer.php pada folder admin yang mengirimkan data dengan php

    // Handle checkbox change
    wakilCheckbox.addEventListener('change', function(){
        if(wakilCheckbox.checked){
            formWakilKandidat.style.display = "block";
            namaWakilKandidatInput.value = initialNamaWakil; // Set value to PHP value
        }else{
            formWakilKandidat.style.display = "none";
            namaWakilKandidatInput.value = ""; // Clear the value
        }
    });

    // Ensure input is cleared before submitting form if checkbox is unchecked
    document.getElementById('kandidatForm').addEventListener('submit', function(event){
        if(!wakilCheckbox.checked){
            namaWakilKandidatInput.value = ""; // Ensure the value is cleared before submit
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const previewButton = document.getElementById('previewButton');
    const fileInput = document.getElementById('fileCsv');
    const previewDiv = document.getElementById('preview-data-peserta');
    const uploadForm = document.getElementById('uploadForm');

    previewButton.addEventListener('click', function () {
        if (fileInput.files.length === 0) {
            console.error('No file selected');
            previewDiv.innerHTML = 'Please select a file first.';
            return;
        }

        const formData = new FormData();
        formData.append('fileCsv', fileInput.files[0]);

        console.log('Sending request to:', uploadForm.action);
        console.log('File selected:', fileInput.files[0]);

        fetch(uploadForm.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status); // Log status code
            console.log('Response headers:', response.headers); // Log headers
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
        
            // Check content type
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.indexOf('application/json') !== -1) {
                return response.json();
            } else {
                throw new Error('Unexpected response type');
            }
        })
        .then(data => {
            console.log('Preview Data:', data); // Use 'data' directly

            previewDiv.innerHTML = '';

            if (!data || data.error) {
                previewDiv.innerHTML = data && data.error ? `<p>${data.error}</p>` : '<p>No data uploaded.</p>';
                return;
            }

            let html = '<table class="table" style="zoom: 90%">';
            html += '<thead><tr><th>No</th><th>Nama Peserta</th><th>Jenis Kelamin</th><th>Nomor Whatsapp</th><th>Tanggal Lahir</th></tr></thead>';
            html += '<tbody>';

            let no = 1;
            data.forEach(row => {
                html += `<tr>
                            <td>${no++}</td>
                            <td>${row.nama_lengkap}</td>
                            <td>${row.jenis_kelamin}</td>
                            <td>${row.nomor_whatsapp}</td>
                            <td>${row.tanggal_lahir}</td>
                        </tr>`;
            });

            html += '</tbody></table>';
            previewDiv.innerHTML = html;

            // Tambahkan tombol import
            const importButton = document.createElement('button');
            importButton.textContent = 'Import Data';
            importButton.classList.add('btn', 'btn-success', 'mt-3');
            importButton.addEventListener('click', function() {
                // Lakukan request import ke database di sini
                importDataToDatabase(data); // Ganti dengan fungsi yang sesuai
            });
            previewDiv.appendChild(importButton);
        })
        .catch(error => {
            console.error('Error:', error);
            previewDiv.innerHTML = 'An error occurred while fetching data.';
        });

        function importDataToDatabase(data) {
            fetch(`/admin/media_pemilihan/${slug}/aksi_import`, { // Ganti dengan URL atau endpoint yang sesuai
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(responseData => {
                console.log('Import response:', responseData);
                // Tambahkan logika atau feedback jika impor berhasil
                if (responseData && responseData.message) {
                    alert(responseData.message);

                    window.location.href = `/admin/media_pemilihan/detail/${slug}`;
                }
            })
            .catch(error => {
                console.error('Error importing data:', error);
                alert('Terjadi kesalahan saat mengimpor data.');
            });
        }
    });
});




$(document).ready(function() {
  // Mengatur tab aktif dari localStorage
  var activeTab = localStorage.getItem('activeTab');
  if (activeTab) {
      $('.nav-tabs li').removeClass('active');
      $('.tab-pane').removeClass('active');
      
      $('.nav-tabs a[href="' + activeTab + '"]').parent().addClass('active');
      $(activeTab).addClass('active');
  }

  // Menyimpan tab aktif ke localStorage saat diklik
  $('.nav-tabs a').on('click', function() {
      var targetTab = $(this).attr('href');
      localStorage.setItem('activeTab', targetTab);
  });

  // Menangani klik pada tombol "Salin Link Pemilihan"
  $('#copy-link-btn').on('click', function(e) {
    e.preventDefault(); // Mencegah tindakan default link

    // Mengambil URL dari elemen dengan ID media-link
    var linkToCopy = $('#copy-link-btn').attr('href');

    // Membuat elemen input sementara untuk menyalin link
    var tempInput = $('<input>');
    $('body').append(tempInput);
    tempInput.val(linkToCopy).select();
    document.execCommand('copy');
    tempInput.remove();

    // Menampilkan pesan pop-up
    $('#popup-message').fadeIn(400).delay(3000).fadeOut(400);
  });
});



