<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    {{-- <link rel="stylesheet" href="style.css" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <title>PATIENT HEALTH QUESTIONNAIRE (PHQ-9)</title>
    <style>
        .mood_disorder_sec {
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            padding: 40px;
            /* text-align: center; */
        }

        .mood_order_btn {
            padding: 5px 20px;
            background: linear-gradient(to top, #08295a, #165dc8);
            color: white !important;
            font-weight: 600;
            width: auto;
            border: none;
        }
    </style>
</head>

<body>



    <!-- ******* MOOD DISORDER QUESTIONNAIRE STATRS ******** -->
    <section class="">
        <div class="container mood_disorder_sec">
            <div class="row">
                @if ($errors->any())
                    <div class="alert alert-danger col-12 col-md-6 offset-md-3">
                        @foreach ($errors->all() as $error)
                            <span role="alert">{{ $error }}</br></span>
                        @endforeach
                    </div>
                @endif
                @if (\Session::has('error'))
                    <div class="alert alert-danger">
                        <ul>
                            <li>{{  \Session::get('error')  }}</li>
                        </ul>
                    </div>
                @endif
                <form action="{{ route('patient_health_store') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{$loc_id}}" name="loc_id" id="loc_id">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2>PATIENT HEALTH QUESTIONNAIRE (PHQ-9)</h2>
                        </div>
                        <img src="https://demo.umbrellamd-video.com/assets/images/logo.png" alt=""
                            style="width:7% ;">
                    </div>
                    <div class="row my-4">
                        <div class="col-md-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label m-0">Name *</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1"
                                placeholder="Enter your name" name="user_name" value="{{ $user->name." ".$user->last_name }}" readonly required>
                            <input type="hidden" name="flag" value="{{ $flag }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label m-0">Date *</label>
                            <input type="date" class="form-control" id="exampleFormControlInput1"
                                placeholder="Enter date" value="<?= date('Y-m-d') ?>" name="date" readonly required>
                        </div>
                    </div>
                    <div>
                        <p>Over the last 2 weeks, how often have you been bothered by any of the following problems?
                        </p>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Not at all (0)</th>
                                <th scope="col">Several (1)</th>
                                <th scope="col">More than half the days (2)</th>
                                <th scope="col">Nearly every day (3)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1. Little interest or pleasure in doing things</td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question1" value="0"
                                        id="Question1">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question1" value="1"
                                        id="Question1">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question1" value="2"
                                        id="Question1">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question1" value="3"
                                        id="Question1">
                                </td>
                            </tr>


                            <tr>
                                <td>2. Feeling down, depressed, or hopeless</td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question2" value="0"
                                        id="Question2">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question2" value="1"
                                        id="Question2">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question2" value="2"
                                        id="Question2">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question2" value="3"
                                        id="Question2">
                                </td>
                            </tr>


                            <tr>
                                <td>3. Trouble falling or staying asleep, or sleeping too much</td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question3" value="0"
                                        id="Question3">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question3" value="1"
                                        id="Question3">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question3" value="2"
                                        id="Question3">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question3" value="3"
                                        id="Question3">
                                </td>
                            </tr>


                            <tr>
                                <td>4. Feeling tired or having little energy</td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question4" value="0"
                                        id="Question4">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question4" value="1"
                                        id="Question4">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question4" value="2"
                                        id="Question4">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question4" value="3"
                                        id="Question4">
                                </td>
                            </tr>

                            <tr>
                                <td>5. Poor appetite or overeating</td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question5" value="0"
                                        id="Question5">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question5" value="1"
                                        id="Question5">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question5" value="2"
                                        id="Question5">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question5" value="3"
                                        id="Question5">
                                </td>
                            </tr>



                            <tr>
                                <td>6. Feeling bad about yourself or that you are a failure or have let yourself or your
                                    family down</td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question6" value="0"
                                        id="Question6">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question6" value="1"
                                        id="Question6">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question6" value="2"
                                        id="Question6">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question6" value="3"
                                        id="Question6">
                                </td>
                            </tr>


                            <tr>
                                <td>7. Trouble concentrating on things, such as reading the newspaper or watching
                                    television
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question7" value="0"
                                        id="Question7">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question7" value="1"
                                        id="Question7">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question7" value="2"
                                        id="Question7">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question7" value="3"
                                        id="Question7">
                                </td>
                            </tr>


                            <tr>
                                <td>8. Moving or speaking so slowly that other people could have noticed. Or the
                                    opposite
                                    being so figety or restless that you have been moving around a lot more than usual
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question8" value="0"
                                        id="Question8">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question8" value="1"
                                        id="Question8">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question8" value="2"
                                        id="Question8">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question8" value="3"
                                        id="Question8">
                                </td>
                            </tr>


                            <tr>
                                <td>9. Thoughts that you would be better off dead, or of hurting yourself</td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question9" value="0"
                                        id="Question9">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question9" value="1"
                                        id="Question9">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question9" value="2"
                                        id="Question9">
                                </td>
                                <td>
                                    <input class="form-check-input" type="radio" name="Question9" value="3"
                                        id="Question9">
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p>(Healthcare professional: For interpretation of TOTAL please refer to accompanying
                                scoring
                                card).</p>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Add columns totals:</label>
                                <input type="text" name="col_total" class="form-control" maxlength="2" required>
                                <div class="form-text">How many 1s,2s and 3s you selected for a total number </div>
                            </div>
                        </div>
                        <div>
                            <b>10. If you checked off any problems, how difficult Not difficult at all have these
                                problems
                                made it for you to do your work, take care of things at home, or get along with other
                                people? *(required)
                            </b>
                            <div class="d-flex justify-content-between my-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="question10"
                                        value="not" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Not difficult at all
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="question10"
                                        value="somewhat" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Somewhat difficult
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="question10"
                                        value="very" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Very difficult
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="question10"
                                        value="extremly" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Extremely difficult
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="mood_order_btn mt-3 ms-auto" type="submit">Submit</button>
                </form>

            </div>
        </div>
    </section>

    <!-- ******* MOOD DISORDER QUESTIONNAIRE ENDS ******** -->




    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>
