@extends('layouts.admin')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Add Payment</h2>

        </div>
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card">
					<div class="header">
						<h2>Payment Information </h2>

					</div>
					<div class="body">
                        <div class="row clearfix">

                            <div class="col-sm-12">
                                <div class="form-group drop-custum">
                                    <select class="form-control show-tick">
                                        <option value="">-- Choose Vandor --</option>
                                        <option>Cash</option>
                                        <option>Cheque</option>
                                        <option>Credit Card</option>
                                        <option>Debit Card</option>
                                        <option>Netbanking</option>
                                        <option>Insurance</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" class="form-control" placeholder="Transection ID">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="datepicker form-control" placeholder="Payment Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" class="form-control" placeholder="Total Amount">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-raised g-bg-cyan">Submit</button>
                                <button type="submit" class="btn btn-raised">Cancel</button>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
    </div>
</section>
@endsection
