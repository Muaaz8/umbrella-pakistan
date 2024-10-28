<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Auth;


class IsabelController extends Controller
{
    public function age_group()
    {
        $head =  env('ISABEL_AUTHORIZATION');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiscsandbox.isabelhealthcare.com/v3/age_groups?language=en&web_service=json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'authorization:'.$head.'',
                'Cookie: _session_id=75fdc6f2d46186ba9ac4fd59e1fc798a; language=en',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        foreach ($response->age_groups->age_group as $grp) {
            DB::table('isabel_age_group')->insert([
                'agegroup_id' => $grp->agegroup_id,
                'name' => $grp->name,
                'yr_from' => $grp->yr_from,
                'yr_to' => $grp->yr_to,
                'branch' => $grp->branch,
                'can_conceive' => $grp->can_conceive,
            ]);
        }
        dd("done");
    }

    public function region()
    {
        $head =  env('ISABEL_AUTHORIZATION');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiscsandbox.isabelhealthcare.com/v3/regions?language=en&web_service=json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'authorization:'.$head.'',
                'Cookie: _session_id=75fdc6f2d46186ba9ac4fd59e1fc798a; language=en',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);

        foreach ($response->travel_history->region as $reg) {
            DB::table('isabel_region')->insert([
                'region_id' => $reg->region_id,
                'region_name' => $reg->region_name,
            ]);
        }
        dd("done");
    }

    public function countries()
    {
        $head =  env('ISABEL_AUTHORIZATION');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiscsandbox.isabelhealthcare.com/v3/countries?language=en&web_service=json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'authorization:'.$head.'',
                'Cookie: _session_id=75fdc6f2d46186ba9ac4fd59e1fc798a; language=en',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);

        foreach ($response->countries->country as $ctry) {
            DB::table('isabel_countries')->insert([
                'country_id' => $ctry->country_id,
                'country_name' => $ctry->country_name,
                'abbreviation' => $ctry->abbreviation,
                'region_id' => $ctry->region_id,
            ]);
        }
        dd("done");
    }

    public static function proquery($problems)
    {
        $head =  env('ISABEL_AUTHORIZATION');

        $temp = '';
        foreach ($problems as $prob) {
            $temp = $temp . $prob . ",";
        }
        $temp = str_replace(" ", "%20", $temp);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiscsandbox.isabelhealthcare.com/v3/convert_to_pro_query?language=en&web_service=json&query=' . $temp,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'authorization:'.$head.'',
                'Cookie: _session_id=799517adee4f9afa39888ede6421ce2e; language=en',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        if(isset($response->pro_query)){
            return ($response->pro_query);
        }else{
            return "Invalid Authentication.";
        }
    }

    public static function getsymptoms($proQuery,$preg)
    {
        $head =  env('ISABEL_AUTHORIZATION');

        $dob = str_replace("-","",Auth::user()->date_of_birth);
        $gender = (Auth::user()->gender)[0];
        if ($gender != 'm' || $gender != 'f' || $gender != 'M' || $gender != 'F') {
            $gender = 'm';
        }
        $proQuery = str_replace(" ", "%20", $proQuery);
        // dd($proQuery,$dob,$gender,$preg);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiscsandbox.isabelhealthcare.com/v3/ranked_differential_diagnoses?language=en&specialties=28&dob='.$dob.'&sex='.$gender.'&pregnant='.$preg.'&region=1&querytext='.$proQuery.'&suggest=Suggest+Differential+Diagnosis&flag=sortbyRW_advanced&searchType=0&web_service=json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'authorization:'.$head.'',
                'Cookie: _session_id=799517adee4f9afa39888ede6421ce2e; language=en',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        return $response->diagnoses_checklist;

    }
}
