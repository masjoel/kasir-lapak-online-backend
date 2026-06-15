@extends('layouts.app')

@section('title', 'Kasir Dashboard')

@push('style')
@endpush

@section('main')
    <div class="main-content">
        @if (auth()->user()->roles == 'admin' || auth()->user()->roles == 'reseller')
            <section class="section">
                <div class="section-header">
                    <h1>Users</h1>
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
                                    <h4>All Trial User</h4>
                                    <div class="card-header-action">
                                        <a href="{{ route('home.export', array_filter(['name' => request('name'), 'is_type' => request('is_type')], fn($v) => $v !== null && $v !== '')) }}"
                                            class="btn btn-success">
                                            <i class="fas fa-file-excel"></i> Export Excel
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="float-right">
                                        <form method="GET" action="{{ route('home') }}" class="d-flex align-items-center"
                                            style="gap: 8px;">
                                            <select class="form-control" name="is_type" style="min-width: 140px;">
                                                <option value="">-- All Type --</option>
                                                <option value="0" {{ request('is_type') === '0' ? 'selected' : '' }}>
                                                    Trial</option>
                                                <option value="1" {{ request('is_type') == '1' ? 'selected' : '' }}>
                                                    Starter</option>
                                                <option value="2" {{ request('is_type') == '2' ? 'selected' : '' }}>
                                                    Basic</option>
                                                <option value="3" {{ request('is_type') == '3' ? 'selected' : '' }}>Pro
                                                </option>
                                            </select>
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Search"
                                                    name="name" value="{{ request('name') }}">
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
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Roles</th>
                                                <th>Status</th>
                                                <th>Phone</th>
                                                <th>Type</th>
                                                <th>Marketing</th>
                                                <th>Created At</th>
                                                @if (auth()->user()->email == 'owner@tokopojok.com')
                                                    <th class="text-center">Action</th>
                                                @endif
                                            </tr>
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>{{ $user->name }}{{ $user->roles == 'reseller' ? ' (' . $user->reseller_id . ')' : '' }}
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ ucwords($user->roles) }}</td>
                                                    <td>
                                                        @if ($user->phone == $user->booking_id)
                                                            <span class="badge badge-primary">Lifetime</span>
                                                        @else
                                                            <span class="badge badge-warning">Trial</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $user->telpon ?? '' }}</td>
                                                    <td class="text-nowrap">
                                                        @if ($user->is_type == 0)
                                                            <span class="badge badge-secondary">Trial</span>
                                                        @elseif ($user->is_type == 1)
                                                            <span class="badge badge-info">Starter</span>
                                                        @elseif ($user->is_type == 2)
                                                            <span class="badge badge-primary">Basic</span>
                                                        @elseif ($user->is_type == 3)
                                                            <span class="badge badge-success">Pro</span>
                                                        @else
                                                            <span class="badge badge-light">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $user->marketing }}</td>
                                                    <td>{{ $user->created_at }}</td>
                                                    @if (auth()->user()->email == 'owner@tokopojok.com')
                                                        <td class="text-nowrap">
                                                            <div class="d-flex justify-content-center">
                                                                @php
                                                                    // $starterAvailable =
                                                                    //     is_null($user->booking_id) &&
                                                                    //     isset($starterCode);
                                                                    // $basicAvailable =
                                                                    //     is_null($user->booking_id) && isset($basicCode);
                                                                    // $proAvailable =
                                                                    //     is_null($user->booking_id) && isset($proCode);
                                                                    $starterAvailable = $user->is_type == 1;
                                                                    $basicAvailable = $user->is_type == 2;
                                                                    $proAvailable = $user->is_type == 3;
                                                                    $upgradeAvailable =
                                                                        $user->is_type > 0 && $user->is_type <= 3;
                                                                @endphp
                                                                <a href="{{ ($starterAvailable ? '#' : $upgradeAvailable) ? route('upgrade-starter', $user->phone) : route('starter', $starterCode->code) . '?phone=' . $user->phone }}"
                                                                    class="btn btn-sm btn-{{ $starterAvailable ? 'secondary' : 'info' }} btn-icon"
                                                                    {{ $starterAvailable ? '' : 'aria-disabled=true tabindex=-1' }}>
                                                                    <i class="fas fa-edit"></i>
                                                                    Starter
                                                                </a>
                                                                <a href="{{ ($basicAvailable ? '#' : $upgradeAvailable) ? route('upgrade-basic', $user->phone) : route('basic', $basicCode->code) . '?phone=' . $user->phone }}"
                                                                    class="btn btn-sm btn-{{ $basicAvailable ? 'secondary' : 'warning' }} btn-icon mx-2"
                                                                    {{ $basicAvailable ? '' : 'aria-disabled=true tabindex=-1' }}>
                                                                    <i class="fas fa-edit"></i>
                                                                    Basic
                                                                </a>
                                                                <a href="{{ ($proAvailable ? '#' : $upgradeAvailable) ? route('upgrade-pro', $user->phone) : route('konfirmasi', $proCode->code) . '?phone=' . $user->phone }}"
                                                                    class="btn btn-sm btn-{{ $proAvailable ? 'secondary' : 'danger' }} btn-icon"
                                                                    {{ $proAvailable ? '' : 'aria-disabled=true tabindex=-1' }}>
                                                                    <i class="fas fa-edit"></i>
                                                                    Pro
                                                                </a>
                                                                @if ($user->device_id == 0)
                                                                    <span class="badge badge-secondary ml-2">Logout</span>
                                                                @else
                                                                    <span class="badge badge-success ml-2" ms-2>Login</span>
                                                                @endif

                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach


                                        </table>
                                    </div>
                                    <div class="float-right">
                                        {{ $users->withQueryString()->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
@endpush
