<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <title>HAMILTON ANXIETY SCALE (HAM-A)</title>
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
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2>HAMILTON ANXIETY SCALE (HAM-A)</h2>
                    </div>
                    <img src="https://demo.umbrellamd-video.com/assets/images/logo.png" alt=""
                        style="width:7% ;">
                </div>
                {{-- <div class="row my-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label m-0">Patient Name: *</label>
                        <input type="text" class="form-control" placeholder="Enter your name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label m-0">Today’s Date: *</label>
                        <input type="date" class="form-control" placeholder="Enter date">
                    </div>
                </div> --}}
                <div>
                    <p>The Hamilton Anxiety Scale (HAM-A) is a rating scale developed to quantify the severity of
                        anxiety symptomatology, often used in psychotropic drug evaluation. It consists of 14 items,
                        each defined by a series of symptoms. Each item is rated on a 5-point scale, ranging from 0 (not
                        present) to 4 (very severe). </p>
                </div>

                <form action="{{ route('anxiety_scale_store') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{$loc_id}}" name="loc_id" id="loc_id">
                    <input type="hidden" name="id" value="{{ $id }}">
                    <input type="hidden" name="flag" value="{{ $flag }}">

                    <div class="d-flex justify-content-between my-3">
                        <b>0= Not present</b>
                        <b>1= Mild</b>
                        <b>2= Moderate</b>
                        <b>3= Severe</b>
                        <b>4= Very Severe</b>
                    </div>

                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>1. ANXIOUS MOOD </b></p>
                            <small>• Worries • Anticipates worst</small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="anxiety" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="anxiety" required> --}}
                        </div>
                    </div>


                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>2. TENSION </b></p>
                            <small> • Startles • Cries easily • Restless • Trembling</small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="tension" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="tension" required> --}}
                        </div>
                    </div>

                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>3. FEARS</b></p>
                            <small> • Fear of the dark • Fear of strangers • Fear of being alone • Fear of animal
                            </small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="fears" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="fears" required> --}}
                        </div>
                    </div>


                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>4. INSOMNIA </b></p>
                            <small> • Difficulty falling asleep or staying asleep • Difficulty with Nightmares</small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="insomnia" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="insomnia" required> --}}
                        </div>
                    </div>

                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>5. INTELLECTUAL </b></p>
                            <small> •Poor concentration • Memory Impairment</small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="intellectual" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="intellectual" required> --}}
                        </div>
                    </div>

                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>6. DEPRESSED MOOD</b></p>
                            <small>• Decreased interest in activities • Anhedoni • Insomnia</small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="depressed" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="depressed" required> --}}
                        </div>
                    </div>



                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>7. SOMATIC COMPLAINTS: MUSCULAR </b></p>
                            <small> •Muscle aches or pains • Bruxism</small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="muscular" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="muscular" required> --}}
                        </div>
                    </div>

                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>8. SOMATIC COMPLAINTS: SENSORY </b></p>
                            <small> •Tinnitus • Blurred vision</small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="sensory" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="sensory" required> --}}
                        </div>
                    </div>


                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>9. CARDIOVASCULAR SYMPTOMS</b></p>
                            <small>•Tachycardia • Palpitations • Chest Pain • Sensation of feeling faintInsert and
                                format
                                text, links, and images here.</small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="cardio" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="cardio" required> --}}
                        </div>
                    </div>

                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>10. RESPIRATORY SYMPTOMS</b></p>
                            <small>• Chest pressure • Choking sensation • Shortness of Breath</small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="respiratory" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="respiratory" required> --}}
                        </div>
                    </div>

                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>11. GASTROINTESTINAL SYMPTOMS </b></p>
                            <small> • Dysphagia • Nausea or Vomiting • Constipation • Weight loss • Abdominal
                                fullness</small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="gastro" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="gastro" required> --}}
                        </div>
                    </div>

                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>12. GENITOURINARY SYMPTOMS </b></p>
                            <small> • Urinary frequency or urgency • Dysmenorrhea • Impotence</small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="genitourinary" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="genitourinary" required> --}}
                        </div>
                    </div>

                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>13. AUTONOMIC SYMPTOMS</b></p>
                            <small> • Dry Mouth• Flushing • Pallor • Sweating</small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="autonomic" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="autonomic" required> --}}
                        </div>
                    </div>

                    <div class="row mt-3 align-items-center">
                        <div class="col-sm-9">
                            <p><b>14. BEHAVIOR AT INTERVIEW </b></p>
                            <small> • Fidgets • Tremor • Pace</small>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label m-0">Score: *</label>
                            <select class="form-select" aria-label="Default select example" name="behavior" required>
                                <option value="" selected>Open this select menu</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            {{-- <input type="number" class="form-control" onKeyPress="if(this.value.length==1) return false;" min="0" max="4" name="behavior" required> --}}
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
