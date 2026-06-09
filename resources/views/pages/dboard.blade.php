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
                                </div>
                                <div class="card-body">
                                    <div class="float-right">
                                        <form method="GET" action="{{ route('home.post') }}">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Search"
                                                    name="name">
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
                                                    <td>{{ $user->marketing }}</td>
                                                    <td>{{ $user->created_at }}</td>
                                                    @if (auth()->user()->email == 'owner@tokopojok.com')
                                                        <td class="text-nowrap">
                                                            <div class="d-flex justify-content-center">
                                                                {{-- @if ($user->phone == $user->booking_id)
                                                                    <span class="badge badge-primary">Lifetime</span>
                                                                @else --}}
                                                                <a href='{{ $user->is_type == 1 ? '#' : '/starter/' . $user->phone }}'
                                                                    class="btn btn-sm btn-{{ $user->is_type == 1 ? 'secondary' : 'info' }} btn-icon"
                                                                    target="_blank">
                                                                    <i class="fas fa-edit"></i>
                                                                    Starter
                                                                </a>
                                                                <a href='{{ $user->is_type == 2 ? '#' : '/basic/' . $user->phone }}'
                                                                    class="btn btn-sm btn-{{ $user->is_type == 2 ? 'secondary' : 'warning' }} btn-icon mx-2"
                                                                    target="_blank">
                                                                    <i class="fas fa-edit"></i>
                                                                    Basic
                                                                </a>
                                                                <a href='{{ $user->is_type == 3 ? '#' : '/konfirmasi/' . $user->phone }}'
                                                                    class="btn btn-sm btn-{{ $user->is_type == 3 ? 'secondary' : 'danger' }} btn-icon"
                                                                    target="_blank">
                                                                    <i class="fas fa-edit"></i>
                                                                    Pro
                                                                </a>
                                                                {{-- @endif --}}
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
