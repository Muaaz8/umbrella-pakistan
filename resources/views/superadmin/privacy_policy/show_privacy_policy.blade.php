@extends('layouts.admin')

@section('content')
<style>
table {
    border-collapse: unset !important;
    width: 100% !important;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Dashboard</h2>
            <small class="text-muted">Welcome to Umbrellamd</small>
        </div>

        <div class="">
            <table class="">
                <tr>
                    <td>{!! ($msg->content) !!}</td>
                </tr>
            </table>

        </div>
        <div class="row d-flex justify-content-center" style="padding-left: 20px">
            <a href="{{ route('view_privacy_policy') }}" class="btn btn-primary">Back</a>
        </div>
</section>
@endsection
