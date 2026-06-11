@extends('layouts.app')

@section('title', $title)

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $title }}</h1>
                <div class="section-header-button">
                    <a href="{{ route('activation-code.create') }}" class="btn btn-primary">Add New</a>
                    <button type="button" class="btn btn-secondary ml-2" id="print-filtered-results">
                        <i class="fas fa-print"></i> Print Filtered
                    </button>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('activation-code.index') }}">{{ $title }}</a>
                    </div>
                    <div class="breadcrumb-item">All {{ $title }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>All {{ $title }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-right">
                                    <form method="GET" action="{{ route('activation-code.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search" name="cari"
                                                value="{{ request('cari') }}">
                                            <select class="form-control" name="is_used">
                                                <option value="">All Status</option>
                                                <option value="true"
                                                    {{ request('is_used') === 'true' ? 'selected' : '' }}>Sudah Digunakan
                                                </option>
                                                <option value="false"
                                                    {{ request('is_used') === 'false' ? 'selected' : '' }}>Belum Digunakan
                                                </option>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="clearfix mb-3"></div>

                                <div class="table-responsive">
                                    <table class="table-striped table">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Tipe</th>
                                            <th>Status</th>
                                            <th>Email</th>
                                            <th>Updated At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        @foreach ($codes as $code)
                                            <tr>
                                                <td>{{ $code->code }}</td>
                                                <td>{{ ucwords($code->type) }}</td>
                                                <td class="text-nowrap">
                                                    @if ($code->is_used == true)
                                                        <span class="badge badge-success">Sudah Digunakan</span>
                                                    @else
                                                        <span class="badge badge-secondary">Belum Digunakan</span>
                                                    @endif
                                                </td>
                                                <td>{{ $code->email }}</td>
                                                <td>{{ $code->updated_at->format('d M Y H:i') }}</td>
                                                <td class="text-nowrap">
                                                    <div class="d-flex justify-content-center">
                                                        {{-- <a href='{{ route('activation-code.edit', $code->id) }}'
                                                            class="btn btn-sm btn-info btn-icon">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a> --}}
                                                        @if ($code->is_used == false)
                                                            <a href='#'
                                                                class="ml-2 btn btn-sm btn-warning btn-icon activate-code-btn"
                                                                data-code="{{ $code->code }}"
                                                                data-type="{{ ucwords($code->type) }}"
                                                                data-email="{{ $code->email }}">
                                                                <i class="fas fa-code"></i>
                                                                Activate
                                                            </a>
                                                        @endif
                                                        <a href='#'
                                                            class="ml-2 btn btn-sm btn-success btn-icon print-activation-code"
                                                            data-code="{{ $code->code }}"
                                                            data-type="{{ ucwords($code->type) }}">
                                                            <i class="fas fa-print"></i>
                                                            Print
                                                        </a>
                                                        {{-- <a href='#' id="delete-data" data-id="{{ $code->id }}"
                                                            class="ml-2 btn btn-sm btn-danger btn-icon">
                                                            <i class="fas fa-times"></i>
                                                            Delete
                                                        </a> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                            <div class="float-right">
                                {{ $codes->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>

    <div class="modal fade" id="activationModal" tabindex="-1" role="dialog" aria-labelledby="activationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activationModalLabel">Activate Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="activationModalForm" action="{{ route('activate-with-code') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="code" id="activation-code">
                        <input type="hidden" name="type" id="activation-type">
                        <div class="form-group">
                            <label for="activation-email">Email</label>
                            <input type="email" class="form-control" id="activation-email" name="email"
                                placeholder="Masukkan email pengguna" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="activationModalSave">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
    <script>
        $(document).on("click", "a#delete-data", function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            showDeletePopup(BASE_URL + '/activation-code/' + id, '{{ csrf_token() }}', '', '',
                BASE_URL + '/activation-code');
        });

        $(document).on('click', 'a.print-activation-code', function(e) {
            e.preventDefault();
            printActivationCode($(this).data('code'), $(this).data('type'));
        });

        $(document).on('click', '#print-filtered-results', function(e) {
            e.preventDefault();
            printFilteredActivationCodes();
        });

        function escapeHtml(text) {
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function printFilteredActivationCodes() {
            let rows = $('table.table tr').not(':first');
            let data = [];

            rows.each(function() {
                let columns = $(this).find('td');
                if (columns.length >= 2) {
                    data.push({
                        code: columns.eq(0).text().trim(),
                        type: columns.eq(1).text().trim(),
                    });
                }
            });

            if (data.length === 0) {
                alert('Tidak ada data untuk dicetak.');
                return;
            }

            let cardsHtml = data.map(function(item) {
                return '<div class="print-card">' +
                    '<div class="print-label">Kode</div>' +
                    '<div class="print-value">' + escapeHtml(item.code) + '</div>' +
                    '<div class="print-label">Tipe</div>' +
                    '<div class="print-value">' + escapeHtml(item.type) + '</div>' +
                    '</div>';
            }).join('');

            let html = '<!DOCTYPE html>' +
                '<html>' +
                '<head>' +
                '<title>Print Filtered Activation Codes</title>' +
                '<style>' +
                '@page { margin: 0.3in; }' +
                'body { font-family: Arial, sans-serif; margin: 0; padding: 12px; color: #000; }' +
                '.print-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; }' +
                '.print-card { border: 1px solid #000; padding: 10px; display: grid; grid-template-columns: auto 1fr; gap: 4px 8px; align-items: center; }' +
                '.print-label { font-weight: 700; font-size: 13px; }' +
                '.print-value { font-size: 13px; }' +
                '.print-title { font-size: 16px; font-weight: 700; margin-bottom: 10px; }' +
                '</style>' +
                '</head>' +
                '<body>' +
                '<div class="print-title">Activation Codes</div>' +
                '<div class="print-grid">' + cardsHtml + '</div>' +
                '</body>' +
                '</html>';

            let printWindow = window.open('', '_blank', 'width=900,height=600');
            if (!printWindow) {
                alert('Popup blokir mencegah tampilan cetak. Silakan izinkan popup untuk situs ini.');
                return;
            }
            printWindow.document.write(html);
            printWindow.document.close();
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            };
        }

        $(document).on('click', 'a.activate-code-btn', function(e) {
            e.preventDefault();
            let code = $(this).data('code');
            let type = $(this).data('type');
            let email = $(this).data('email') || '';

            $('#activationModalLabel').text('Activate ' + type + ' Code');
            $('#activation-code').val(code);
            $('#activation-type').val(type.toLowerCase());
            $('#activation-email').val(email);
            $('#activationModal').modal('show');
        });

        $('#activationModalForm').on('submit', function(e) {
            let email = $('#activation-email').val();
            if (!email) {
                e.preventDefault();
                alert('Email harus diisi.');
                return;
            }
        });

        function printActivationCode(code, type) {
            let printWindow = window.open('', '_blank', 'width=520,height=360');
            if (!printWindow) {
                alert('Popup blokir mencegah tampilan cetak. Silakan izinkan popup untuk situs ini.');
                return;
            }
            let html = '<!DOCTYPE html>' +
                '<html>' +
                '<head>' +
                '<title>Print Activation Code</title>' +
                '<style>' +
                '@page { margin: 0.2in; size: auto; }' +
                'body { font-family: Arial, sans-serif; margin: 0; padding: 8px; color: #000; }' +
                '.print-card { border: 1px solid #000; padding: 8px 10px; display: inline-grid; grid-template-columns: auto 1fr; gap: 4px 8px; max-width: 320px; }' +
                '.print-title { grid-column: 1 / -1; font-size: 14px; font-weight: 700; margin-bottom: 4px; }' +
                '.print-label { font-weight: 700; font-size: 12px; }' +
                '.print-value { font-size: 12px; }' +
                '.label-cell { align-self: center; }' +
                '</style>' +
                '</head>' +
                '<body>' +
                '<div class="print-card">' +
                '<div class="print-title">Activation Code</div>' +
                '<div class="print-label label-cell">Kode</div>' +
                '<div class="print-value">' + escapeHtml(code) + '</div>' +
                '<div class="print-label label-cell">Tipe</div>' +
                '<div class="print-value">' + escapeHtml(type) + '</div>' +
                '</div>' +
                '</body>' +
                '</html>';
            printWindow.document.write(html);
            printWindow.document.close();
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            };
        }
    </script>
@endpush
