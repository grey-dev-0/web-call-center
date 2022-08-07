@extends('wcc::master')

@push('pre-scripts')
    <script type="text/javascript" src="{{asset(mix('js/manifest.js', 'vendor/wcc'))}}"></script>
    <script type="text/javascript" src="{{asset(mix('js/vendors.js', 'vendor/wcc'))}}"></script>
    <script type="text/javascript" src="{{asset(mix('js/vue.min.js', 'vendor/wcc'))}}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{asset(mix('css/vendors.css', 'vendor/wcc'))}}" type="text/css">
@endpush

@section('content')
    <div id="wcc-app" class="container pt-2">
        <h3 class="float-left">Welcome {{$customer->name}}</h3>
        <a href="{{route('wcc-logout')}}" class="float-right btn btn-outline-secondary">Logout</a>
        <div class="clearfix"></div>
        <div class="card">
            <div class="card-body p-0">
                <table id="organizations-list" class="table table-hover table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Organization</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="organizations.length" v-for="(organization, i) in organizations">
                        <tr :class="inCall == organization.id? 'bg-success' : ''">
                            <td class="align-middle">@{{ organization.name }}</td>
                            <td class="text-right">
                                <div class="btn btn-sm btn-outline-success" v-if="!inCall" @click="call(organization)">Call</div>
                                <div class="btn btn-sm btn-outline-danger" v-else-if="inCall == organization.id" @click="hangup(true)">Hang Up</div>
                            </td>
                        </tr>
                    </template>
                    <tr v-else>
                        <td colspan="2" class="text-center text-muted">No Organizations Found</td>
                    </tr>
                    </tbody>
                </table>
                <ul class="pagination pagination-sm justify-content-center my-2" v-if="lastPage != 1">
                    <li :class="'page-item' + (currentPage == 1? ' disabled' : '')" title="First"><a class="page-link" href="javascript:void(0);" @click="currentPage != 1? first() : false">&laquo; first</a></li>
                    <li :class="'page-item' + (currentPage == 1? ' disabled' : '')" title="Previous"><a class="page-link" href="javascript:void(0);" @click="currentPage != 1? previous() : false">&lsaquo; previous</a></li>
                    <li :class="'page-item' + (currentPage == lastPage? ' disabled' : '')" title="Next"><a class="page-link" href="javascript:void(0);" @click="currentPage != lastPage? next() : false">next &rsaquo;</a></li>
                    <li :class="'page-item' + (currentPage == lastPage? ' disabled' : '')" title="Last"><a class="page-link" href="javascript:void(0);" @click="currentPage != lastPage? last() : false">last &raquo;</a></li>
                </ul>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script type="text/javascript">var rtc = @json($rtc);</script>
    <script type="text/javascript" src="{{asset(mix('js/customer.js', 'vendor/wcc'))}}"></script>
@endpush
