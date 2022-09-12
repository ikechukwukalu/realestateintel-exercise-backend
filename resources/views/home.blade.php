@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                   <div class="row w-50 mx-auto">
                        <div class="btn-group ">
                            <a href="{{ route('externalBooks') }}" class="btn btn-secondary">
                                Requirement 1
                            </a>
                            <a href="{{ route('localBooks') }}" class="btn btn-dark">
                                Requirement 2
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
