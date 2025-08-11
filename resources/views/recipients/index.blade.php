@extends('layouts.app')

@section('title', 'Data Penerima')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Form Search --}}
        <form action="{{ route('recipients.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2"
                   placeholder="Cari nama anak..."
                   value="{{ request('search') }}"
                   oninput="this.form.submit()"> {{-- auto submit tanpa klik tombol --}}
        </form>

        <a href="{{ route('recipients.import') }}" class="btn btn-success ms-2">
            <i class="fas fa-plus me-2"></i>Import Excel
        </a>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>QR Code</th>
                            <th>Nama Anak</th>
                            <th>Nama Ayah</th>
                            <th>Nama Ibu</th>
                            <th>Sekolah</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recipients as $recipient)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $recipient->qr_code }}</span>
                                </td>
                                <td>{{ $recipient->child_name }}</td>
                                <td>{{ $recipient->Ayah_name }}</td>
                                <td>{{ $recipient->Ibu_name }}</td>
                                <td>{{ $recipient->school_name }}</td>
                                <td>{{ $recipient->class }}</td>

                                <style>
                                    .status-box {
                                        display: inline-flex;
                                        align-items: center;
                                        gap: 5px;
                                        padding: 4px 10px;
                                        border-radius: 5px;
                                        font-weight: 600;
                                        font-size: 0.85rem;
                                        color: white;
                                        text-decoration: none;
                                    }
                                    .status-red { background-color: #dc3545; }
                                    .status-yellow { background-color: #ffc107; color: #212529; }
                                    .status-green { background-color: #28a745; }
                                    .status-box i { font-size: 1rem; }
                                </style>

                                <td>
                                    @if ($recipient->is_distributed && $recipient->registrasi)
                                        <a href="#" class="status-box status-green">
                                            <i class="bi bi-check-all"></i> Completed
                                        </a>
                                    @elseif ($recipient->registrasi && !$recipient->is_distributed)
                                        <a href="#" class="status-box status-yellow">
                                            <i class="bi bi-check"></i> Sudah registrasi
                                        </a>
                                    @else
                                        <a href="#" class="status-box status-red">
                                            <i class="bi bi-x"></i> Belum registrasi
                                        </a>
                                    @endif
                                </td>

                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('recipients.show', $recipient) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('recipients.edit', $recipient) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('recipients.qr-code', $recipient) }}" class="btn btn-sm btn-secondary" target="_blank">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                        @if ($recipient->is_distributed)
                                            <a href="{{ route('recipients.receipt', $recipient) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('recipients.destroy', $recipient) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data penerima</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $recipients->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
