@extends('layouts.dashboard_doctor')
@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection
@section('page_title')
    <title>UHCS - Psychiatrist Info</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script type="text/javascript">
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="//cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="{{ asset('assets/js/chatbot.js') }}"></script>
    <script src="{{ asset('assets/js/pateint_form.js') }}"></script>
    <script>
        CKEDITOR.replace('helping', {
            enterMode: CKEDITOR.ENTER_BR,
            on: {
                'instanceReady': function(evt) {
                    evt.editor.execCommand('');
                }
            },
        });
        CKEDITOR.replace('skilled', {
            enterMode: CKEDITOR.ENTER_BR,
            on: {
                'instanceReady': function(evt) {
                    evt.editor.execCommand('');
                }
            },
        });
        CKEDITOR.instances['helping'].setData("{!! isset($information->help)?$information->help:'' !!}");
        CKEDITOR.instances['skilled'].setData("{!! isset($information->skills)?$information->skills:'' !!}");
    </script>
    <script>
        $(".js-select2").select2({
            closeOnSelect: false,
            scrollAfterSelect: false,
            placeholder: "  Select Option",
            allowHtml: false,
            allowClear: true,
            tags: true,
        });
        $(document).ready(function () {
            $.ajax({
                type: 'POST',
                url: "/get_therapy_slots",
                data: {
                    date: moment($('#therapydate').val(), 'mm-DD-YYYY').format('YYYY-mm-DD'),
                    id:$('#event_id').val(),
                },
                async: false,
                success: function(data)
                {
                $('#therapy_start_slots').html('');
                $.each (data, function (key, start) {
                    $('#therapy_start_slots').append('<option value="'+start.time+'">'+start.time+'</option>');
                });
                }
            });
        });
        document.getElementById("event_form").onkeypress = function(e) {
            var key = e.charCode || e.keyCode || 0;
            if (key == 13) {
                e.preventDefault();
            }
        }
    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-end p-0">
                            <div>
                                <h3>Event Information</h3>
                            </div>

                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="wallet-table" style="border-radius: 18px;">
                            <form action="{{ route('update_therapy_event') }}" method="post">
                                @csrf
                                <div class=" p-3">
                                    <div class="row align-items-center">
                                        <div class="mb-4 col-md-6">
                                            <input id="therapydate" type="text" class="form-control"
                                                value="{{ $information->date }}" readonly/>
                                            <input type="hidden" name="date" value="{{ $information->edate }}">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <input type="hidden" name="AvailabilityTitle" value="Therapy"
                                                    id="title" placeholder="Title">
                                                <input type="hidden" value="#008000" name="AvailabilityColor"
                                                    id="color" />
                                                <input type="hidden" value="{{ $information->event_id }}" name="event_id"
                                                    id="event_id" />
                                                <input type='hidden' class="form-control" name="AvailabilityStart"
                                                    class="form-control" id="start" />
                                                <select id="therapy_start_slots" onchange="remove_error()"
                                                    class="form-control" name="startTimePicker" required>
                                                    <option value="">Select Start Time</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="z-index: -1;">
                                            <label class="fw-bolder" for="selectmedicine">CLIENT CONCERNS I TREAT</label>
                                            <select class="js-select2" name="concerns[]" multiple="multiple">
                                                <option value="Worthlessness">Worthlessness</option>
                                                <option value="Worry">Worry</option>
                                                <option value="Women-Issues">Women's Issues</option>
                                                <option value="Trust-Issues">Trust Issues</option>
                                                <option value="Suicidal-Ideation-and-Behavior">Suicidal Ideation and
                                                    Behavior</option>
                                                <option value="Stress">Stress</option>
                                                <option value="Spirituality">Spirituality</option>
                                                <option value="Social-Anxiety-Phobia">Social Anxiety / Phobia</option>
                                                <option value="Shame">Shame</option>
                                                <option value="Self-Love">Self-Love</option>
                                                <option value="Self-Esteem">Self-Esteem</option>
                                                <option value="Self-Doubt">Self-Doubt</option>
                                                <option value="Self-Confidence">Self-Confidence</option>
                                                <option value="Self-Criticism">Self-Criticism</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6" style="z-index: -1;">
                                            <label class="fw-bolder" for="selectmedicine">SERVICES I PROVIDE</label>
                                            <select class="js-select2" name="services[]" multiple="multiple">
                                                <option value="Consultation">Consultation</option>
                                                <option value="Family">Family Therapy</option>
                                                <option value="Individual">Individual Therapy & Counseling</option>
                                                <option value="Marriage">Marriage, Couples, or Relationship Counseling
                                                </option>
                                                <option value="Telehealth">Telehealth</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="helping">My Approach to Helping:</label>
                                            <textarea class="form-control" id="helping" name="helping" rows="4"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="skilled">SPECIFIC ISSUE(S) I'M SKILLED AT
                                                HELPING WITH:</label>
                                            <textarea class="form-control" id="skilled" name="skilled" rows="4"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="text-end">
                                            <button type="submit" class="btn descrip-save-btn">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
