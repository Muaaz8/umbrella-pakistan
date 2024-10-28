@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tbl Orders
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($tblOrders, ['route' => ['orders.update', $tblOrders->id], 'method' => 'patch']) !!}

                        @include('tbl_orders.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection