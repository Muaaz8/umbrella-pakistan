<!-- Question Field -->
<div class="form-group">
    {!! Form::label('question', 'Question:') !!}
    <p>{{ $tblFaq->question }}</p>
</div>

<!-- Answer Field -->
<div class="form-group">
    {!! Form::label('answer', 'Answer:') !!}
    {!! $tblFaq->answer !!}
</div>

<!-- Status Field -->
@if(auth()->user()->user_type!='admin')

<div class="form-group">
    {!! Form::label('labtests_ids', 'Labtests:') !!}

    @foreach (json_decode($test_names) as $item)
        <p>{{ $item }}</p>
    @endforeach
   
</div>
@endif