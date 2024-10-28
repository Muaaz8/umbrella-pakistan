<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <title>MOOD</title>
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
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2>THE MOOD DISORDER QUESTIONNAIRE</h2>
                        <p><b>Instructions:</b> Please answer each question to the best of your ability </p>
                    </div>
                    <img src="https://demo.umbrellamd-video.com/assets/images/logo.png" alt=""
                        style="width:7% ;">
                </div>
                {{-- <div class="row my-4">
                    <div class="col-md-6 mb-3">
                        <label for="exampleFormControlInput1" class="form-label m-0">Name *</label>
                        <input type="email" class="form-control" id="exampleFormControlInput1"
                            placeholder="Enter your name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="exampleFormControlInput1" class="form-label m-0">Date *</label>
                        <input type="email" class="form-control" id="exampleFormControlInput1"
                            placeholder="Enter date">
                    </div>
                </div> --}}
                <form action="{{ route('mood_disorder_store') }}" id="form1" method="POST">
                    @csrf
                    <input type="hidden" value="{{$loc_id}}" name="loc_id" id="loc_id">
                    <input type="hidden" name="id" value="{{ $id }}">
                    <input type="hidden" name="flag" value="{{ $flag }}">

                    <div>
                        <h5> 1. Has there ever been a period of time when you were not your usual self and...</h5>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Yes</th>
                                <th scope="col">No</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>...you felt so good or so hyper that other people thought you were not your normal
                                    self
                                    or you were so hyper that you got into trouble? <span class="text-danger" id="Question1a-error"></span> </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1a'
                                            id="Question1a" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1a'
                                            id="Question1a" value="n">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>...you were so irritable that you shouted at people or started fights or arguments?
                                    <span class="text-danger" id="Question1b-error"></span></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1b'
                                            id="Question1b" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1b'
                                            id="Question1b" value="n">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>...you felt much more self-confident than usual? <span class="text-danger" id="Question1c-error"></span></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1c'
                                            id="Question1c" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1c'
                                            id="Question1c" value="n">
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td>...you got much less sleep than usual and found you didn’t really miss it? <span class="text-danger" id="Question1d-error"></span></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1d'
                                            id="Question1d" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1d'
                                            id="Question1d" value="n">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>...you were much more talkative or spoke much faster than usual? <span class="text-danger" id="Question1e-error"></span></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1e'
                                            id="Question1e" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1e'
                                            id="Question1e" value="n">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>...thoughts raced through your head or you couldn’t slow your mind down? <span class="text-danger" id="Question1f-error"></span></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1f'
                                            id="Question1f" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1f'
                                            id="Question1f" value="n">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>...you were so easily distracted by things around you that you had trouble
                                    concentrating
                                    or staying on track? <span class="text-danger" id="Question1g-error"></span></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1g'
                                            id="Question1g" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1g'
                                            id="Question1g" value="n">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>...you had much more energy than usual? <span class="text-danger" id="Question1h-error"></span></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1h'
                                            id="Question1h" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1h'
                                            id="Question1h" value="n">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>...you were much more active or did many more things than usual? <span class="text-danger" id="Question1i-error"></span></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1i'
                                            id="Question1i" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1i'
                                            id="Question1i" value="n">
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td>...you were much more social or outgoing than usual, for example, you telephoned
                                    friends in the middle of the night? <span class="text-danger" id="Question1j-error"></span></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1j'
                                            id="Question1j" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1j'
                                            id="Question1j" value="n">
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td>...you were much more interested in sex than usual? <span class="text-danger" id="Question1k-error"></span></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1k'
                                            id="Question1k" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1k'
                                            id="Question1k" value="n">
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td>...you did things that were unusual for you or that other people might have thought
                                    were excessive, foolish, or risky? <span class="text-danger" id="Question1l-error"></span></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1l'
                                            id="Question1l" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1l'
                                            id="Question1l" value="n">
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td>...spending money got you or your family into trouble? <span class="text-danger" id="Question1m-error"></span></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1m'
                                            id="Question1m" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question1m'
                                            id="Question1m" value="n">
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td><b>2.</b> If you checked YES to more than one of the above, have several of these
                                    ever happened during the same period of time?
                                    <span class="text-danger" id="Question2-error"></span></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question2'
                                            id="Question2" value="y">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name='Question2'
                                            id="Question2" value="n">
                                    </div>
                                </td>
                            </tr>




                        </tbody>
                    </table>

                    <div class="mt-3">
                        <h6>3. How much of a problem did any of these cause you – like being unable to work; having
                            family,
                            money or legal troubles; getting into arguments or fights? Please circle one response only *
                            <span class="text-danger" id="Question3-error"></span>
                        </h6>
                        <div class="my-2">
                            <!-- <div class="form-check"> -->
                            <input class="form-check-input" type="radio" name="Question3" id="Question3"
                                value="No">
                            <label class="form-check-label" for="Question3">No Problem</label>

                            <input class="form-check-input" type="radio" name="Question3" id="Question3"
                                value="Minor">
                            <label class="form-check-label" for="Question3">Minor Problem</label>

                            <input class="form-check-input" type="radio" name="Question3" id="Question3"
                                value="Moderate">
                            <label class="form-check-label" for="Question3">Moderate Problem</label>

                            <input class="form-check-input" type="radio" name="Question3" id="Question3"
                                value="Serious">
                            <label class="form-check-label" for="Question3">Serious Problem</label>

                            <!-- </div> -->
                        </div>
                    </div>

                    <div class="my-3">
                        <h6>4. Have any of your blood relatives (i.e. children, siblings, parents, grandparents, aunts,
                            uncles) had manic-depressive illness or bipolar disorder?*<span class="text-danger" id="Question4-error"></span>
                        </h6>
                        <div class="my-2">
                            <!-- <div class="form-check"> -->
                            <input class="form-check-input" type="radio" name="Question4" id="Question4"
                                value="y">
                            <label class="form-check-label" for="Question4">Yes</label>

                            <input class="form-check-input" type="radio" name="Question4" id="Question4"
                                value="n">
                            <label class="form-check-label" for="Question4">No</label>

                            <!-- </div> -->
                        </div>
                    </div>

                    <div>
                        <h6>5. Has a health professional ever told you that you have manic-depressive illness or bipolar
                            disorder?*<span class="text-danger" id="Question5-error"></span></h6>
                        <div class="my-2">
                            <!-- <div class="form-check"> -->
                            <input class="form-check-input" type="radio" name="Question5" id="Question5"
                                value="y">
                            <label class="form-check-label" for="Question5">Yes</label>

                            <input class="form-check-input" type="radio" name="Question5" id="Question5"
                                value="n">
                            <label class="form-check-label" for="Question5">No</label>

                            <!-- </div> -->
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $('#form1').submit(function (e) {
                $("input:radio").each(function(){
                    var name= $(this).attr('name');
                    if($("input:radio[name="+name+"]:checked").length){
                        check = true;
                        $("#"+name+"-error").html('');
                    }
                    else{
                        check=false;
                        $("#"+name+"-error").html('required');
                    }
                });
                return check;
            });
        });
    </script>

</body>

</html>
