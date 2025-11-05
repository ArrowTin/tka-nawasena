@extends('layouts/layoutMaster')

@section('title', 'Template - TKA Nawasena')

@section('page-style')
    @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
@endsection

@section('content')
    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h5 class="card-header">Table Basic</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Client</th>
                        <th>Users</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <tr>
                        <td>
                            <i class="icon-base ti tabler-brand-angular icon-md text-danger me-4"></i>
                            <span class="fw-medium">Angular Project</span>
                        </td>
                        <td>Albert Cook</td>
                        <td>
                            <ul class="list-unstyled m-0 avatar-group d-flex align-items-center">
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    class="avatar avatar-xs pull-up" title="Lilian Fuller">
                                    <img src="{{ asset('assets/img/avatars/5.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </li>
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                                    <img src="{{ asset('assets/img/avatars/6.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </li>
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    class="avatar avatar-xs pull-up" title="Christina Parker">
                                    <img src="{{ asset('assets/img/avatars/7.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </li>
                            </ul>
                        </td>
                        <td><span class="badge bg-label-primary me-1">Active</span></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base ti tabler-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                            class="icon-base ti tabler-pencil me-1"></i>
                                        Edit</a>
                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                            class="icon-base ti tabler-trash me-1"></i>
                                        Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><i class="icon-base ti tabler-brand-react-native icon-md text-info me-4"></i> <span
                                class="fw-medium">React Project</span></td>
                        <td>Barry Hunter</td>
                        <td>
                            <ul class="list-unstyled m-0 avatar-group d-flex align-items-center">
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    class="avatar avatar-xs pull-up" title="Lilian Fuller">
                                    <img src="{{ asset('assets/img/avatars/5.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </li>
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                                    <img src="{{ asset('assets/img/avatars/6.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </li>
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    class="avatar avatar-xs pull-up" title="Christina Parker">
                                    <img src="{{ asset('assets/img/avatars/7.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </li>
                            </ul>
                        </td>
                        <td><span class="badge bg-label-success me-1">Completed</span></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base ti tabler-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                            class="icon-base ti tabler-pencil me-2"></i>
                                        Edit</a>
                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                            class="icon-base ti tabler-trash me-2"></i>
                                        Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <i class="icon-base ti tabler-brand-vue icon-md text-success me-4"></i>
                            <span class="fw-medium">VueJs Project</span>
                        </td>
                        <td>Trevor Baker</td>
                        <td>
                            <ul class="list-unstyled m-0 avatar-group d-flex align-items-center">
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    class="avatar avatar-xs pull-up" title="Lilian Fuller">
                                    <img src="{{ asset('assets/img/avatars/5.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </li>
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                                    <img src="{{ asset('assets/img/avatars/6.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </li>
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    class="avatar avatar-xs pull-up" title="Christina Parker">
                                    <img src="{{ asset('assets/img/avatars/7.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </li>
                            </ul>
                        </td>
                        <td><span class="badge bg-label-info me-1">Scheduled</span></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="icon-base ti tabler-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                            class="icon-base ti tabler-pencil me-2"></i>
                                        Edit</a>
                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                            class="icon-base ti tabler-trash me-2"></i>
                                        Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <i class="icon-base ti tabler-brand-bootstrap icon-md text-primary me-4"></i>
                            <span class="fw-medium">Bootstrap Project</span>
                        </td>
                        <td>Jerry Milton</td>
                        <td>
                            <ul class="list-unstyled m-0 avatar-group d-flex align-items-center">
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    class="avatar avatar-xs pull-up" title="Lilian Fuller">
                                    <img src="{{ asset('assets/img/avatars/5.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </li>
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                                    <img src="{{ asset('assets/img/avatars/6.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </li>
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    class="avatar avatar-xs pull-up" title="Christina Parker">
                                    <img src="{{ asset('assets/img/avatars/7.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </li>
                            </ul>
                        </td>
                        <td><span class="badge bg-label-warning me-1">Pending</span></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="icon-base ti tabler-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                            class="icon-base ti tabler-pencil me-2"></i>
                                        Edit</a>
                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                            class="icon-base ti tabler-trash me-2"></i>
                                        Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->
@endsection
