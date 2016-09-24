@extends('layouts.base')

@section('content')
    <div class="row">
        @foreach($employees as $employee)
            <div class="col-lg-4">
                <div class="contact-box">
                    <a href="{!! route('employees.edit', [$employee]) !!}">
                        <div class="col-sm-4">
                            <div class="text-center">
                                <img alt="image" class="img-circle m-t-xs img-responsive"
                                     src="{!! $employee->avatar_url !!}">
                                <div class="m-t-xs font-bold">{!! $employee->duty !!}</div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <h3><strong>{{ $employee->full_name }}</strong></h3>
                        </div>
                        <div class="clearfix"></div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
