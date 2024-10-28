    <table class="table table-hover" id="tblFaqs-table">
        <thead>
            <tr>
                <th>Question</th>
                <!-- <th>Answer</th> -->
                <th colspan="5">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tblFaqs as $tblFaq)
                <tr>
                    <td>{{ $tblFaq->question }}</td>
                    <!-- <td>{{ $tblFaq->answer }}</td> -->
                    <td>
                        {!! Form::open(['route' => ['faqs.destroy', $tblFaq->id], 'method' => 'delete']) !!}
                        <div class='btns-group'>
                            <a href="{{ route('faqs.show', [$tblFaq->id]) }}" class='action-btn'><i
                                    class="fa fa-eye"></i></a>
                            <a href="{{ route('faqs.edit', [$tblFaq->id]) }}" class='action-btn'><i
                                    class="fa fa-pen"></i></a>
                            {{-- {!!  Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'action-btn', 'onclick' => "return confirm('Are you sure?')"]) !!} --}}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
