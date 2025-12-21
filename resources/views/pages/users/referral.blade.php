@extends('layouts.app')

@section('title', 'Referral')
@push('style')
@endpush


@section('main')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="container-fluid py-4">
                <div class="card">
                    <div class="card-body">
                        <h3>🚀 Statistik Referral</h3>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>
                                        <h6>🔗 Klik</h6>
                                    </td>
                                    <td>
                                        Jumlah orang mengklik tautan referral
                                    </td>
                                    <td>
                                        <h6>{{ number_format($viewer) }}</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6>👥 Pendaftar</h6>
                                    </td>
                                    <td>
                                        Jumlah orang yang mendaftar melalui tautan
                                    </td>
                                    <td>
                                        <h6>{{ number_format($referral) }}</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6>⏳ Menunggu Konfirmasi</h6>
                                    </td>
                                    <td>
                                        Komisi yang akan Anda dapatkan setelah referral Anda membeli aplikasi
                                    </td>
                                    <td>
                                        <h6>{{ number_format($potensiKomisi) }}</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6>💰 Total Komisi</h6>
                                    </td>
                                    <td>
                                        Total komisi yang siap masuk ke rekening Anda
                                    </td>
                                    <td>
                                        <h6>{{ number_format($totalKomisi - $bayarKomisi) }}</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6>✅ Sudah Dibayarkan</h6>
                                    </td>
                                    <td>
                                        Total komisi yang sudah ditransfer
                                    </td>
                                    <td>
                                        <h6>{{ number_format($bayarKomisi) }}</h6>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card my-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5>👥 Daftar Referral</h5>
                            <form action="">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search" name="search"
                                        value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @if ($users->isEmpty())
                            <p class="text-center text-muted">Belum ada pembeli dari link Anda.</p>
                        @else
                            <table class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th class="text-center">Status<a
                                                href="?sort_field=status&sort_order={{ $sortDirection == 'desc' ? 'asc' : 'desc' }}&search={{ request('search') }}"><i
                                                    class="fa fa-sort float-end"></i></a></th>
                                        <th>Join</th>
                                        <th class="text-end">Komisi</th>
                                        <th class="text-end">Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $limit = 10; @endphp
                                    @foreach ($users as $index => $order)
                                        @php
                                            $komisi = $order->phone == $order->booking_id ? 50000 : 0;
                                            $status = $komisi > 0 ? 'lifetime' : 'trial';
                                        @endphp
                                        <tr>
                                            <td>{{ $limit * $users->currentPage() - $limit + $index + 1 }}</td>
                                            <td>{{ $order->name ?? '-' }}</td>
                                            <td>{{ $order->email ?? '-' }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge badge-{{ $status == 'lifetime' ? 'primary' : 'warning' }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                            <td class="text-end">Rp {{ number_format($komisi, 0, ',', '.') }}</td>
                                            <td class="text-end">
                                                <span id="{{ $order->is_bayar == 0 ? 'bayar' : '' }}"
                                                    data-id="{{ $order->id }}"
                                                    class="badge badge-{{ $order->is_bayar == '1' ? 'success' : 'danger' }} status-bayar"
                                                    style="cursor: pointer;">
                                                    {{ $order->is_bayar == '1' ? 'Telah ditransfer' : 'Pending' }}
                                                </span>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                        <div class="my-5 content-center">
                            {{-- {{ $users->links() }} --}}
                            {{ $users->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.status-bayar', function() {
            const id = $(this).data('id');
            const span = $(this);

            Swal.fire({
                title: 'Status Transfer',
                text: "Ubah status menjadi telah ditransfer?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('reseller.bayar') }}",
                        type: 'POST',
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                span.removeClass('badge-danger').addClass('badge-success')
                                    .text('Telah ditransfer');
                                alertSuccess('', response.message);
                            } else {
                                showFailedAlert('Gagal: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            showWarningAlert('Terjadi kesalahan: ' + xhr.statusText);
                        }
                    });
                }
            })
        });
    </script>
@endpush
