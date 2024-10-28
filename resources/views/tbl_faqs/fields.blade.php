<!-- Question Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('question', 'Question:') !!}
    {!! Form::text('question', null, ['class' => 'form-control']) !!}
</div>

<!-- Answer Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('answer', 'Answer:') !!}
    {!! Form::textarea('answer', null, ['class' => 'form-control']) !!}
</div>
@if(auth()->user()->user_type!='admin')
 <div class="form-group col-sm-12">
    <p>Add Tests</p>
    <select style="width: 100%;" class="form-control testFAQ" name="faq_for_test[]" multiple="multiple">
    </select>
</div>
@endif


<!-- Status Field -->
<!-- <div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!} -->
{!! Form::hidden('status', 1, ['class' => 'form-control']) !!}
<!-- </div> -->

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('faqs.index') }}" class="btn btn-primary">Cancel</a>
</div>