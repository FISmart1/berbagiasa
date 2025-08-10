@extends('layouts.app')

@section('title')
Registrasi
@endsection

@section('content')
<div class="container mt-4">
    <div class="card shadow" style="max-width: 500px;">
        <div class="card-header">
            <h5 class="mb-0">Scan QR Code</h5>
        </div>
        <div class="card-body">
            <form id="verifyForm">
                @csrf
                <div class="mb-3">
                    <label for="qr_code" class="form-label">Masukkan Kode QR</label>
                    <input type="text" name="qr_code" id="qr_code" class="form-control"
                           placeholder="Scan atau ketik kode QR di sini..." autofocus required>
                    <small class="form-text text-muted">
                        Gunakan scanner atau ketik manual kode QR
                    </small>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search me-2"></i>Verifikasi
                </button>
            </form>

            <!-- Hasil scan -->
            <div id="result" class="mt-4" style="display: none;">
                <h6 class="fw-bold mb-3">Data Penerima</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0" style="width: auto; min-width: 250px;">
                        <tbody>
                            <tr>
                                <th>Nama</th>
                                <td id="child_name"></td>
                            </tr>
                            <tr>
                                <th>Nama Sekolah</th>
                                <td id="school_name"></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td id="address"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button id="confirmBtn" class="btn btn-success btn-sm mt-3">✅ Registrasikan</button>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    white-space: nowrap;
    padding: 4px 8px;
    font-size: 0.9rem;
}
.table td {
    padding: 4px 8px;
    font-size: 0.9rem;
}
</style>

<script>
document.getElementById('verifyForm').addEventListener('submit', function(e) {
    e.preventDefault();

    fetch('{{ route("registration.verify") }}', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('result').style.display = 'block';
            document.getElementById('child_name').textContent = data.recipient.child_name;
            document.getElementById('school_name').textContent = data.recipient.school_name;
            document.getElementById('address').textContent = data.recipient.address;

            document.getElementById('confirmBtn').onclick = function() {
                let formData = new FormData();
                formData.append('qr_code', document.getElementById('qr_code').value);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route("registration.confirm") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(resp => {
                    if (resp.success) {
                        alert('Registrasi Berhasil ✅');
                        location.reload();
                    } else {
                        alert(resp.error);
                    }
                });
            };
        } else {
            alert(data.error);
        }
    });
});
</script>


@endsection
