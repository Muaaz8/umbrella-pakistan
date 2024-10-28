@extends('layouts.frontend')

@section('content')
<div class="col-md-8 offset-2">
    <div id="appointment-form-holder">
        <form name="medical_profile" action="{{route('store_medical_history')}}" method="post"
            class="row appointment-form">
            <h1>Medical Profile</h1>
            <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                    <label><b>Allergies to medications, radiations, dyes or other substances</b></label>
                    <div class="form-line">
                        <input type="text" name="allergies" class="form-control"
                            placeholder="No/Yes. (If yes, then please provide list of medicines and type of reactions)">
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                    <p><b>Medical History And Review Of Symptoms</b></p>
                    <div class="checkbox form-check">
                        <input id="m1" class="form-check-input" name="symp[]" value="hypertension"
                            type="checkbox"><label for="m1">Hypertension</label>
                    </div>
                    <div class="checkbox form-check">
                        <input id="m2" class="form-check-input" name="symp[]" value="diabetes" type="checkbox"><label
                            for="m2">Diabetes</label>
                    </div>
                    <div class="checkbox form-check">
                        <input id="m3" class="form-check-input" name="symp[]" value="cancer" type="checkbox"><label
                            for="m3">Cancer</label>
                    </div>
                    <div class="checkbox form-check">
                        <input id="m4" class="form-check-input" name="symp[]" value="heart" type="checkbox"><label
                            for="m4">Heart Disease</label>
                    </div>
                    <div class="checkbox form-check">
                        <input id="m5" class="form-check-input" name="symp[]" value="chest" type="checkbox"><label
                            for="m5">Chest Pain/chest tightness</label>
                    </div>
                    <div class="checkbox form-check">
                        <input id="m6" class="form-check-input" name="symp[]" value="shortness" type="checkbox"><label
                            for="m6">Shortness of breath</label>
                    </div>
                    <div class="checkbox form-check">
                        <input id="m7" class="form-check-input" name="symp[]" value="swollen" type="checkbox"><label
                            for="m7">Swollen Ankles</label>
                    </div>
                    <div class="checkbox form-check">
                        <input id="m8" class="form-check-input" name="symp[]" value="palpitation" type="checkbox"><label
                            for="m8">Palpitation/Irregular Heartbeat</label>
                    </div>
                    <div class="checkbox form-check">
                        <input id="m9" class="form-check-input" name="symp[]" value="stroke" type="checkbox"><label
                            for="m9">Stroke</label>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="">
                                <div class="header">
                                    <h2>Family History<small></small></h2>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <p class="text-bold"><b>Has any of your family member (including parents,
                                                grandparents, and siblings) ever had the following</b></p>
                                        <div class="col-md-12 row">
                                            <div class="col-md-4">
                                                <p><b>Disease</b></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><b>Which family member?</b></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><b>Approx. age when diagnosed</b></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-md-12 row">
                                            <div class="col-md-4">
                                                <span><b>Cancer</b></span>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="f_cancer">
                                                    <option value="">None</option>
                                                    <option value="parent">Parent</option>
                                                    <option value="grand">Grandparent</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mt-0">
                                                    <div class="form-line">
                                                        <input type="text" name="f_cancer_age" class="form-control"
                                                            placeholder="Age">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="col-md-4">
                                                <span><b>Hypertension</b></span>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="f_hypertension">
                                                    <option value="">None</option>
                                                    <option value="parent">Parent</option>
                                                    <option value="grand">Grandparent</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mt-0">
                                                    <div class="form-line">
                                                        <input type="text" name="f_hypertension_age"
                                                            class="form-control" placeholder="Age">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="col-md-4">
                                                <span><b>Heart Disease</b></span>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="f_heart">
                                                    <option value="">None</option>
                                                    <option value="parent">Parent</option>
                                                    <option value="grand">Grandparent</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mt-0">
                                                    <div class="form-line">
                                                        <input type="text" name="f_heart_age" class="form-control"
                                                            placeholder="Age">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="col-md-4">
                                                <span><b>Diabetes</b></span>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="f_diabetes">
                                                    <option value="">None</option>
                                                    <option value="parent">Parent</option>
                                                    <option value="grand">Grandparent</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mt-0">
                                                    <div class="form-line">
                                                        <input type="text" name="f_diabetes_age" class="form-control"
                                                            placeholder="Age">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="col-md-4">
                                                <span><b>Stroke</b></span>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="f_stroke">
                                                    <option value="">None</option>
                                                    <option value="parent">Parent</option>
                                                    <option value="grand">Grandparent</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mt-0">
                                                    <div class="form-line">
                                                        <input type="text" name="f_stroke_age" class="form-control"
                                                            placeholder="Age">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="col-md-4">
                                                <span><b>Mental Disease</b></span>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="f_mental">
                                                    <option value="">None</option>
                                                    <option value="parent">Parent</option>
                                                    <option value="grand">Grandparent</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mt-0">
                                                    <div class="form-line">
                                                        <input type="text" name="f_mental_age" class="form-control"
                                                            placeholder="Age">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="col-md-4">
                                                <span><b>Drugs/Alcohol Addiction</b></span>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="f_drugs">
                                                    <option value="">None</option>
                                                    <option value="parent">Parent</option>
                                                    <option value="grand">Grandparent</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mt-0">
                                                    <div class="form-line">
                                                        <input type="text" name="f_drugs_age" class="form-control"
                                                            placeholder="Age">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="col-md-4">
                                                <span><b>Glaucoma</b></span>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="f_galucoma">
                                                    <option value="">None</option>
                                                    <option value="parent">Parent</option>
                                                    <option value="grand">Grandparent</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mt-0">
                                                    <div class="form-line">
                                                        <input type="text" name="f_galucoma_age" class="form-control"
                                                            placeholder="Age">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="col-md-4">
                                                <span><b>Bleeding Disease</b></span>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="f_bleeding">
                                                    <option value="">None</option>
                                                    <option value="parent">Parent</option>
                                                    <option value="grand">Grandparent</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mt-0">
                                                    <div class="form-line">
                                                        <input type="text" name="f_bleeding_age" class="form-control"
                                                            placeholder="Age">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <span><b>Others</b></span>
                                                    </div>
                                                    <div class="col-md-9 ">
                                                        <div class="form-group mt-0">
                                                            <div class="form-line">
                                                                <input type="text" name="f_others_name"
                                                                    placeholder="Name of disease" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="f_others">
                                                    <option value="">None</option>
                                                    <option value="parent">Parent</option>
                                                    <option value="grand">Grandparent</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mt-0">
                                                    <div class="form-line">
                                                        <input type="text" name="f_others_age" class="form-control"
                                                            placeholder="Age">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12 form-btn">
                                                <button type="submit"
                                                    class="btn btn-blue blue-hover submit">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div> <!-- END APPOINTMENT FORM -->
</div>
@endsection