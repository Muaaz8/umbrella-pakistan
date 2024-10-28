<!-- Order State Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order_state', 'Order State:') !!}
    {!! Form::text('order_state', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Order Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order_id', 'Order Id:') !!}
    {!! Form::text('order_id', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Order Sub Id Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('order_sub_id', 'Order Sub Id:') !!}
    {!! Form::textarea('order_sub_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Customer Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('customer_id', 'Customer Id:') !!}
    {!! Form::text('customer_id', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Total Field -->
<div class="form-group col-sm-6">
    {!! Form::label('total', 'Total:') !!}
    {!! Form::text('total', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Shipping Total Field -->
<div class="form-group col-sm-6">
    {!! Form::label('shipping_total', 'Shipping Total:') !!}
    {!! Form::text('shipping_total', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Total Tax Field -->
<div class="form-group col-sm-6">
    {!! Form::label('total_tax', 'Total Tax:') !!}
    {!! Form::text('total_tax', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Billing Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('billing', 'Billing:') !!}
    {!! Form::textarea('billing', null, ['class' => 'form-control']) !!}
</div>

<!-- Shipping Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('shipping', 'Shipping:') !!}
    {!! Form::textarea('shipping', null, ['class' => 'form-control']) !!}
</div>

<!-- Payment Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('payment', 'Payment:') !!}
    {!! Form::textarea('payment', null, ['class' => 'form-control']) !!}
</div>

<!-- Payment Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payment_title', 'Payment Title:') !!}
    {!! Form::text('payment_title', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Payment Method Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payment_method', 'Payment Method:') !!}
    {!! Form::text('payment_method', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Cart Items Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('cart_items', 'Cart Items:') !!}
    {!! Form::textarea('cart_items', null, ['class' => 'form-control']) !!}
</div>

<!-- Tax Lines Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('tax_lines', 'Tax Lines:') !!}
    {!! Form::textarea('tax_lines', null, ['class' => 'form-control']) !!}
</div>

<!-- Shipping Lines Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('shipping_lines', 'Shipping Lines:') !!}
    {!! Form::textarea('shipping_lines', null, ['class' => 'form-control']) !!}
</div>

<!-- Coupon Lines Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('coupon_lines', 'Coupon Lines:') !!}
    {!! Form::textarea('coupon_lines', null, ['class' => 'form-control']) !!}
</div>

<!-- Currency Field -->
<div class="form-group col-sm-6">
    {!! Form::label('currency', 'Currency:') !!}
    {!! Form::text('currency', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Order Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order_status', 'Order Status:') !!}
    {!! Form::text('order_status', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('orders.index') }}" class="btn btn-default">Cancel</a>
</div>
