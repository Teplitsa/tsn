@extends('layouts.base')

@section('content')
    <div class="row">
        @foreach($roles as $role)
            <div class="col-lg-4">
                <div class="contact-box">
                    <a href="{!! route('role.edit', [$role]) !!}">
                        <div class="col-sm-8">
                            <h3><strong>{{ $role->full_name }}</strong></h3>
                        </div>
                        <div class="clearfix"></div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
