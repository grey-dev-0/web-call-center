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
    <div id="wcc-app" class="container-fluid pt-2">
        <h1>Welcome {{$agent->name}}</h1>
        <div class="card">
            <div class="card-body p-0">
                <table id="customers-list" class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Joined At</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="customers.length" v-for="(customer, i) in customers">
                        <tr :class="inCall? 'bg-success' : ''">
                            <td>
                                @{{ customer.name }}
                                <span class="btn btn-outline-success ml-1" v-if="!inCall && i == 0" @click="pick(customer)"></span>
                            </td>
                            <td>@{{ customer.created_at }}</td>
                        </tr>
                    </template>
                    <tr v-else>
                        <td colspan="2" class="text-center text-muted">No Pending Calls</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script type="text/javascript">var rtc = @json($rtc);</script>
    <script type="text/javascript" src="{{asset(mix('js/agent.js', 'vendor/wcc'))}}"></script>
@endpush
