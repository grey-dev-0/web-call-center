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
        <h3 class="float-left">Welcome {{$agent->name}}</h3>
        <a href="{{route('wcc-logout')}}" class="float-right btn btn-outline-secondary">Logout</a>
        <div class="clearfix"></div>
        <div class="card">
            <div class="card-body p-0">
                <table id="customers-list" class="table table-hover table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Joined At</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="customers.length" v-for="(customer, i) in customers">
                        <tr :class="(inCall && i == 0)? 'bg-success' : ''">
                            <td>
                                @{{ customer.name }}
                                <span class="btn btn-sm btn-outline-success ml-4" v-if="!inCall && i == 0" @click="pick(customer)">Pick Up</span>
                                <span class="btn btn-sm btn-outline-danger ml-4" v-else-if="inCall == customer.id" @click="hangup(true)">Hang Up</span>
                            </td>
                            <td>@{{ customer.joined_at }}</td>
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
