<?php

namespace App\Http\Controllers;

use App\QuestRequest;
use App\VendorAccount;
use App\User; // If Message is used
use Aranyasen\HL7\Message; // If Segment is used
use Aranyasen\HL7\Segment; // If MSH is used
use Aranyasen\HL7\Segments\MSH; // If MSH is used
use App\Mail\ReportFile;
use Mail;
use Illuminate\Support\Facades\DB;
use Log;
use App\Mail\RequisitionFile;
use App\LabOrder;
use App\Models\QuestDataSites;
use App\Session;
use Illuminate\Support\Facades\Log as FacadesLog;

class HL7Controller extends Controller
{
    public function hl7Decode($str_hl7)
    {
        $mshData = [];
        $pidData = [];
        $msg = new Message($str_hl7);

        $data2 = $msg->toString(true);
        if ($msg->isOru()) //result report
        {
            $obxSegment = 0;
            $nteSegment = 0;
            if ($msg->hasSegment('OBX')) {
                $obxSegment = $msg->getSegmentsByName('OBX');
            }
            if ($msg->hasSegment('NTE')) {
                $nteSegment = $msg->getSegmentsByName('NTE');
            }
            if ($msg->hasSegment('MSH')) {
                $mhsSegment = $msg->getSegmentsByName('MSH')[0];
                $mshData['dateTimeMSG'] = $mhsSegment->getDateTimeOfMessage();
                $mshData['messageID'] = $mhsSegment->getMessageControlId();
                $mshData['messageType'] = $mhsSegment->getMessageType();
                $mshData['processingId'] = $mhsSegment->getProcessingId();
                $mshData['receivingName'] = $mhsSegment->getReceivingApplication();
                if (is_array($mhsSegment->getReceivingFacility())) {
                    $mshData['ReceivingFacility'] = $mhsSegment->getReceivingFacility()[0];
                } else {
                    $mshData['ReceivingFacility'] = $mhsSegment->getReceivingFacility();
                }

                $mshData['SendingApplication'] = $mhsSegment->getSendingApplication();
                $mshData['SendingFacility'] = $mhsSegment->getSendingFacility();
                $mshData['TriggerEvent'] = $mhsSegment->getTriggerEvent();
                $mshData['VersionId'] = $mhsSegment->getVersionId();
            }

            if ($msg->hasSegment('PID')) {
                $pidSegment = $msg->getSegmentsByName('PID')[0];
                $pidData['Patient Name'] = $pidSegment->getPatientName();
                $pidData['Gender'] = $pidSegment->getSex();
                $pidData['ID'] = $pidSegment->getID();
                $pidData['Marital Status'] = $pidSegment->getMaritalStatus();
                $pidData['Nationality'] = $pidSegment->getNationality();
                $pidData['Patient Address'] = $pidSegment->getPatientAddress();
                $pidData['Patient ID'] = $pidSegment->getPatientID();
                $pidData['Phone Number Business'] = $pidSegment->getPhoneNumberBusiness();
                $pidData['Date Time Of Birth'] = $pidSegment->getDateTimeOfBirth();
                $pidData['Mothers Identifier'] = $pidSegment->getMothersIdentifier();
                $pidData['Country Code'] = $pidSegment->getCountryCode();
                $pidData['Mothers Maiden Name'] = $pidSegment->getMothersMaidenName();
                $pidData['Multiple Birth Indicator'] = $pidSegment->getMultipleBirthIndicator();
                $pidData['Patient Account Number'] = $pidSegment->getPatientAccountNumber();
                $pidData['Patient Alias'] = $pidSegment->getPatientAlias();
                $pidData['Patient Identifier List'] = $pidSegment->getPatientIdentifierList();
                $pidData['Phone Number Home'] = $pidSegment->getPhoneNumberHome();
                $pidData['Primary Language'] = $pidSegment->getPrimaryLanguage();
                $pidData['Race'] = $pidSegment->getRace();
                $pidData['Religion'] = $pidSegment->getReligion();
                $pidData['SSN Number'] = $pidSegment->getSSNNumber();
                $pidData['Veterans Military Status'] = $pidSegment->getVeteransMilitaryStatus();
                $pidData['Alternate Patient ID'] = $pidSegment->getAlternatePatientID();
            }
            if ($msg->hasSegment('OBR')) {
                $obrSegment = $msg->getSegmentsByName('OBR');
            }

            if ($msg->hasSegment('SPM')) {
                $spmSegment = $msg->getSegmentsByName('SPM')[0];
                // dd($spmSegment);
                $arrSPM = $this->read_spm($spmSegment);
                // dd($arrSPM);

            } else {
                $arrSPM = "";
            }
            if (is_array($nteSegment)) {
                $test_non_performed = $this->test_not_performed($obxSegment[0], $nteSegment[0]);
            } else {
                $test_non_performed[0] = "";
            }
            $result_type = $this->result_type($obxSegment);
            $patient_matching = $this->patient_matching($pidSegment);
            $pat_info = $this->patient_information($pidSegment);

            $all_segments = $msg->getSegments();

            $i = 0;
            $next_obr = 0;
            $arrOBX = [];
            $arrOBR = [];
            $arrNTE = [];
            foreach ($all_segments as $key => $segment) {
                if ($segment->getField(0) == "OBR") {
                    $arrOBR[$i] = $this->read_obr($segment);
                    $start = $key;
                    for ($x = $key + 1; $x < sizeOf($all_segments); $x++) {
                        if ($all_segments[$x]->getField(0) == "OBR") {
                            $next_obr = $x;
                            break;
                        }
                        if ($x + 1 == sizeOf($all_segments)) {
                            $next_obr = sizeOf($all_segments);
                            break;
                        }
                    }
                    for ($y = $start; $y < $next_obr; $y++) {
                        if ($all_segments[$y]->getField(0) == "OBX") {
                            $last_obx = $all_segments[$y];
                            $obx = $this->read_obx($all_segments[$y]);
                            $arrOBX[$i]['OBX'][$y] = $obx;
                            for ($z = $y + 1; $z < $next_obr; $z++) {
                                if ($all_segments[$z]->getField(0) == "NTE") {
                                    $nte = $this->comment($all_segments[$z]);
                                    if ($nte != " ") {
                                        // dd($nte);
                                        $arrNTE['OBX'][$y][$z] = $nte;
                                    }
                                } else {
                                    break;
                                }
                            }
                        }
                    }
                    $i++;
                }
            }
            if ($msg->hasSegment('ORC')) {
                $orcSegment = $msg->getSegmentsByName('ORC')[0];
                // dd($orcSegment->getField(12));
                $provider_info = $this->get_provider_info($orcSegment);
            } else {
                $provider_info['name'] = '';
                $provider_info['NPI'] = '';
                $provider_info['ID'] = '';
                $provider_info['status'] = 'Missing ORC';
                $provider_info['error_flag'] = 1;
            }
            $order_matching = $this->order_matching($arrOBR[0], $pidSegment, $orcSegment);
            $report_pdf = $last_obx->getField(5)[4];
            return array(
                'client_info' => $mshData,
                'performed' => $test_non_performed, //not used for pdf report
                'result_type' => $result_type,
                'patient_info' => $pat_info,
                'patient_matching' => $patient_matching,
                'provider_matching' => $provider_info,
                'order_matching' => $order_matching,
                'report_pdf' => $report_pdf,
                'arrOBR' => $arrOBR,
                'arrOBX' => $arrOBX,
                'arrNTE' => $arrNTE,
                'arrSPM' => $arrSPM
            );
        }
    }
    public function converToObjToArray($data)
    {
        return json_decode(json_encode($data), true);
    }
    public function test_not_performed($obx, $nte)
    {
        $result = array();
        if (isset($obx->getField(32)[3])) {
            if ($obx->getField(32)[3] == 'TNP') {
                $result[0] = "TNP";
                $result[1] = $nte->getField(3);
            } else {
                $result[0] = "Test performed";
                $result[1] = "";
            }
        } else {
            $result[0] = "Test performed";
            $result[1] = "";
        }
        return $result;
    }
    public function patient_information($pid)
    {
        // dd($patient['specimen']=$pid->getField(18));
        if ($pid->getField(2) != null) {
            if (is_array($pid->getField(2))) {
                $patient['pat_id'] = $pid->getField(2)[0];
            } else {
                $patient['pat_id'] = $pid->getField(2);
            }
        }
        if ($pid->getField(3) != null) {
            if (is_array($pid->getField(3))) {
                $patient['specimen'] = $pid->getField(3)[0];
            } else {
                $patient['specimen'] = $pid->getField(3);
            }
        }
        if ($pid->getField(18) != null) {
            $patient['requisition'] = $pid->getField(18);
        }

        if ($pid->getField(5)[1] != null) {
            $patient['fname'] = $pid->getField(5)[1];
        }

        if ($pid->getField(5)[0] != null) {
            $patient['lname'] = $pid->getField(5)[0];
        }

        if (isset($pid->getField(5)[2])) {
            $patient['mname'] = $pid->getField(5)[2];
        }

        if ($pid->getField(8) != null) {
            $patient['gender'] = $pid->getField(8);
        }

        if ($pid->getField(7) != null) {
            $patient['dob'] = substr($pid->getField(7), 4, 2) . '/' .
                substr($pid->getField(7), 6, 2) . '/' .
                substr($pid->getField(7), 0, 4);
        }

        return $patient;
    }
    public function get_provider_info($orc)
    {
        if (isset($orc->getfield(12)[0])) {
            $orc_npi = $orc->getfield(12)[0];
            $umb_doc = User::where('nip_number', $orc_npi)->first();
            if (isset($umb_doc->id)) {
                $provider_info['name'] = $orc->getField(12)[1] . ',' . isset($orc->getField(12)[2]);
                $provider_info['NPI'] = $orc_npi;
                $provider_info['ID'] = $umb_doc->id;
                $provider_info['status'] = 'No missing info';
                $provider_info['error_flag'] = 0;
            } else {
                $provider_info['name'] = $orc->getField(12)[1] . ',' . isset($orc->getField(12)[2]);
                $provider_info['NPI'] = $orc_npi;
                $provider_info['ID'] = '';
                $provider_info['status'] = 'Doctor NPI Not Matched In System';
                $provider_info['error_flag'] = 1;
            }
            return $provider_info;
        } else {
            $provider_info['name'] = '';
            $provider_info['NPI'] = '';
            $provider_info['ID'] = '';
            $provider_info['status'] = 'NPI missing';
            $provider_info['error_flag'] = 1;
            return $provider_info;
        }
    }
    public function patient_matching($pid)
    {
        $result['status'] = '';
        $result['attributes'] = '';
        $result['error_flag'] = 0;
        $patient = User::find($pid->getField(2));
        if ($patient != null) {
            // match quest and our system's date of birth format
            $pat_dob = User::dob_year_dashes($patient->date_of_birth, 'int') .
                User::dob_date_dashes($patient->date_of_birth, 'int') .
                User::dob_month_dashes($patient->date_of_birth, 'int');
            // dd($pat_dob);
            // if all required fields are present in quest PID segment
            if (
                isset($pid->getField(5)[1])
                && (isset($pid->getField(5)[0]))
                && (($pid->getField(8)) != null)
                && (($pid->getField(7)) != null)
            ) {
                if ((strtolower($patient->name) == strtolower($pid->getField(5)[1]))
                    && (strtolower($patient->last_name) == strtolower($pid->getField(5)[0]))
                    && (strtolower(substr($patient->gender, 0, 1)) == strtolower($pid->getField(8)))
                    && ($pat_dob == $pid->getField(7))
                ) {
                    $result['status'] = "Patient matched";
                    $result['attributes'] = 'PID Patient ID: ' . $pid->getField(2) .
                        ' | PID Patient Name: ' . $pid->getField(5)[1] .
                        ' | PID Patient Last Name: ' . $pid->getField(5)[0] .
                        ' | PID Patient Gender: ' . $pid->getField(8) .
                        ' | PID Patient DOB: ' . $pid->getField(7) .
                        ' | DB Patient Name: ' . $patient->name .
                        ' | DB Patient Last Name: ' . $patient->last_name .
                        ' | DB Patient Gender: ' . $patient->gender .
                        ' | DB Patient DOB: ' . $pat_dob;
                    $result['error_flag'] = 0;
                    return $result;
                    // return true;
                } else {
                    Log::channel('questErrorLogging')->info(
                        'Patient not matched. Request: Patient ID: ' . $pid->getField(2) .
                            ' | Patient Name: ' . $pid->getField(5)[1] .
                            ' | Patient Last Name: ' . $pid->getField(5)[0] .
                            ' | Patient Gender: ' . $pid->getField(8) .
                            ' | Patient DOB: ' . $pid->getField(7) .
                            ' | Database: Patient Name: ' . $patient->name .
                            ' | Patient Last Name: ' . $patient->last_name .
                            ' | Patient Gender: ' . $patient->gender .
                            ' | Patient DOB: ' . $pat_dob
                    );

                    $result['status'] =  "Patient not matched";
                    $result['attributes'] = 'Patient ID: ' . $pid->getField(2) .
                        ' | Patient Name: ' . $pid->getField(5)[1] .
                        ' | Patient Last Name: ' . $pid->getField(5)[0] .
                        ' | Patient Gender: ' . $pid->getField(8) .
                        ' | Patient DOB: ' . $pid->getField(7) .
                        ' | Database: Patient Name: ' . $patient->name .
                        ' | Patient Last Name: ' . $patient->last_name .
                        ' | Patient Gender: ' . $patient->gender .
                        ' | Patient DOB: ' . $pat_dob;
                    $result['error_flag'] = 1;
                    return $result;
                }
            } else {
                Log::channel('questErrorLogging')->info('Missing fields in PID. Request: Patient ID: ' . $pid->getField(2));
                $result['status'] =  "Missing fields in PID";
                $missing = '';
                if (!isset($pid->getField(5)[1])) {
                    $missing .= 'First Name |';
                }
                if (!isset($pid->getField(5)[0])) {
                    $missing .= 'Last Name |';
                }
                if (($pid->getField(8)) == null) {
                    $missing .= 'Gender |';
                }
                if (($pid->getField(7)) == null) {
                    $missing .= 'DOB |';
                }
                $result['attributes'] = 'Missing :' . $missing;
                $result['error_flag'] = 1;
                return $result;
            }
            // name, last name, date of birth and gender must match
        } else {
            Log::channel('questErrorLogging')->info(
                'Patient does not exist in system. Request: Patient ID: ' . $pid->getField(2) .
                    ' | Patient Name: ' . $pid->getField(5)[1] .
                    ' | Patient Last Name: ' . $pid->getField(5)[0] .
                    ' | Patient Gender: ' . $pid->getField(8)
            );
            // dd('log');
            $result['status'] =  "Patient does not exist in system";
            $result['attributes'] = 'Patient ID: ' . $pid->getField(2) .
                ' | Patient Name: ' . $pid->getField(5)[1] .
                ' | Patient Last Name: ' . $pid->getField(5)[0] .
                ' | Patient Gender: ' . $pid->getField(8);
            $result['error_flag'] = 1;
            return $result;
        }
    }
    public function order_matching($obr, $pid, $orc)
    {
        // dd($obr);
        $order_matching['status'] = '';
        $order_matching['error_flag'] = 0;
        $order_id = $obr['placer_order_num'];
        if ($order_id != '') {
            $lab_orders = LabOrder::where('sub_order_id', $order_id)->get();
            $order_matching['placer_order_num'] = $order_id;
            if ($lab_orders->count() == 0) {
                $order_matching['status'] = 'Placer OrderID Not In System';
                $order_matching['error_flag'] = 1;
            } else {
                if ($lab_orders[0]->user_id != $pid->getField(2)) {
                    $order_matching['status'] .= 'Order Patient Missmatched | ';
                    $order_matching['error_flag'] = 1;
                }
                $session = Session::find($lab_orders[0]->session_id);
                $umb_doctor = User::find($session->doctor_id);
                $hl7_npi = $orc->getfield(12)[0];
                if ($umb_doctor->nip_number != $hl7_npi) {
                    $order_matching['status'] .= 'Order NPI Missmatched | ';
                    $order_matching['error_flag'] = 1;
                }
                if ($order_matching['error_flag'] == 0) {
                    $order_matching['status'] = 'Success! Everything matched';
                }
            }
        } else {
            $order_matching['placer_order_num'] = $order_id;
            $order_matching['status'] = 'Missing Placer OrderID';
            $order_matching['error_flag'] = 1;
        }
        return $order_matching;
    }
    public function read_obx($obx)
    {
        $result = array();
        if ($obx->getField(5) != "DNR") {
            if ($obx->getField(3) != null) {
                if ($obx->getField(11) == 'F') {
                    if (isset($obx->getField(3)[0])) {
                        $result["Test ID"] = $obx->getField(3)[0];
                        // echo ('<p><b>Test ID:</b>' . $obx->getField(3)[0] . '</p>');
                    }

                    if (isset($obx->getField(3)[3])) {
                        $result["."] = $obx->getField(3)[3];
                        // echo ('<p><b></b>' . $obx->getField(3)[3] . '</p>');
                    }

                    if (isset($obx->getField(3)[1])) {
                        $result["Test Name"] = $obx->getField(3)[1];
                        // echo ('<p><b>Test Name:</b>' . $obx->getField(3)[1] . '</p>');
                    }

                    if (isset($obx->getField(3)[4])) {
                        $result[".."] = $obx->getField(3)[4];
                        // echo ('<p><b></b>' . $obx->getField(3)[4] . '</p>');
                    }

                    if ($obx->getField(6) != null) {
                        if (is_array($obx->getField(6))) {
                            $result["Unit"] = $obx->getField(6)[0];
                        } else {
                            $result["Unit"] = $obx->getField(6);
                        }
                        // echo ('<p><b>Unit:</b>' . $obx->getField(6) . '</p>');
                    }

                    if ($obx->getField(5) != null) {
                        if (is_array($obx->getField(5))) {
                            $result["Results"] = $obx->getField(5)[0];
                        } else {
                            $result["Results"] = $obx->getField(5);
                        }

                        // echo ('<p><b>Results:</b>' . $obx->getField(5) . '</p>');
                    }

                    if ($obx->getField(7) != null) {
                        $result["Reference Range"] = $obx->getField(7);
                        // echo ('<p>Reference Range:' . $obx->getField(7) . '</p>');
                    }

                    if ($obx->getField(8) != null) {
                        $result["Abnormal"] = $obx->getField(8);
                        // echo ('<p>Abnormal:' . $obx->getField(8) . '</p>');
                    }

                    if ($obx->getField(11) != null) {
                        $result["Status"] = $obx->getField(11);
                        // echo ('<p><b>Status:</b>' . $obx->getField(11) . '</p>');
                    }

                    if ($obx->getField(15) != null) {
                        if (is_array($obx->getField(15))) {
                            $result["Lab"] = $obx->getField(15)[0];
                        } else {
                            $result["Lab"] = $obx->getField(15);
                        }

                        // echo ('<p><b>Lab:</b>' . $obx->getField(15)[0] . '</p>');
                    }
                } else if ($obx->getField(11) != 'F') {
                    // dd($obx);
                    if (isset($obx->getField(3)[1])) {
                        $result["Test Name"] = $obx->getField(3)[1];
                        // echo ('<p><b>Test Name:</b>' . $obx->getField(3)[1] . '</p>');
                    }
                    $result["Status"] = "DNR";
                }
            }
        } else {
            $result["Status"] = $obx->getField(5);
        }
        return $result;
    }
    public function reading_print($obx)
    {
        $result = "";

        if ($obx->getField(3) != null) {
            if ($obx->getField(11) == 'F') {
                if (isset($obx->getField(3)[0])) {
                    $result .= "Test ID:" . $obx->getField(3)[0] . '\n';
                    echo ('<p><b>Test ID:</b>' . $obx->getField(3)[0] . '</p>');
                }

                if (isset($obx->getField(3)[3])) {
                    $result .= "Test ID:" . $obx->getField(3)[0] . '\n';
                    echo ('<p><b></b>' . $obx->getField(3)[3] . '</p>');
                }

                if (isset($obx->getField(3)[1])) {
                    $result .= "Test Name:" . $obx->getField(3)[1] . '\n';
                    echo ('<p><b>Test Name:</b>' . $obx->getField(3)[1] . '</p>');
                }

                if (isset($obx->getField(3)[4])) {
                    $result .= ":" . $obx->getField(3)[4] . '\n';
                    echo ('<p><b></b>' . $obx->getField(3)[4] . '</p>');
                }

                if ($obx->getField(6) != null) {
                    $result .= "Unit:" . $obx->getField(6) . '\n';
                    echo ('<p><b>Unit:</b>' . $obx->getField(6) . '</p>');
                }

                if ($obx->getField(5) != null) {
                    $result .= "Results:" . $obx->getField(5) . '\n';
                    echo ('<p><b>Results:</b>' . $obx->getField(5) . '</p>');
                }

                if ($obx->getField(7) != null) {
                    $result .= "Reference Range:" . $obx->getField(7) . '\n';
                    echo ('<p>Reference Range:' . $obx->getField(7) . '</p>');
                }

                if ($obx->getField(8) != null) {
                    $result .= "Abnormal:" . $obx->getField(8) . '\n';
                    echo ('<p>Abnormal:' . $obx->getField(8) . '</p>');
                }

                if ($obx->getField(11) != null) {
                    $result .= "Status:" . $obx->getField(11) . '\n';
                    echo ('<p><b>Status:</b>' . $obx->getField(11) . '</p>');
                }

                if ($obx->getField(15)[0] != null) {
                    $result .= "Lab:" . $obx->getField(15)[0] . '\n';
                    echo ('<p><b>Lab:</b>' . $obx->getField(15)[0] . '</p>');
                }
            } else if ($obx->getField(11) == 'F') {
            }
        }
        return $result;
    }

    public function comment($nte)
    {
        $comment = "";
        if ($nte->getField(3) != null) {
            $comment .= $nte->getField(3);
            // echo ("<p><b>Comment: </b>" . $nte->getField(3) . '</p>');
        }
        return $comment;
    }
    public function result_type($obxs)
    {
        // dd($obxs);
        $result = "Final";
        foreach ($obxs as $obx) {
            if ($obx->getField(11) != null) {
                // $result.=$obx->getField(11);

                if ($obx->getField(11) == 'P') {
                    return $result = "Partial";
                }
                // } else if ($obx->getField(11) == 'F') {
                //     return $result.="<p><b>Result Type: </b> Final Result</p>";
                // } else if ($obx->getField(11) == 'A') {
                //     return $result.="<p><b>Result Type: </b> Amended Result</p>";
                // } else if ($obx->getField(11) == 'I') {
                //     return $result.="<p><b>Result Type: </b> In Process Result</p>";
                // } else if ($obx->getField(11) == 'B') {
                //     return $result.="<p><b>Result Type: </b> Appended Result</p>";
                // } else if ($obx->getField(11) == 'N') {
                //     return $result.="<p><b>Result Type: </b> Not Asked</p>";
                // } else if ($obx->getField(11) == 'X') {
                //     return $result.="<p><b>Result Type: </b> Not Possible</p>";
                // } else if ($obx->getField(11) == 'D') {
                //     return $result.="<p><b>Result Type: </b> Delete</p>";
                // } else if ($obx->getField(11) == 'W') {
                //     return $result.="<p><b>Result Type: </b> Wrong</p>";
                // }
            } else {
                return $result = "Invalid HL7 format";
            }
        }
        return $result;
    }
    public function read_obr($obr)
    {
        // dd($obr);
        $result = array();
        if ($obr->getField(2) != null) {
            if (is_array($obr->getField(2))) {
                $result['placer_order_num'] = $obr->getField(2)[0];
            } else {
                $result['placer_order_num'] = $obr->getField(2);
            }
        } else {
            $result['placer_order_num'] = "";
        }
        if ($obr->getField(3) != null) {
            if (is_array($obr->getField(3))) {
                $result['filler_order_num'] = $obr->getField(3)[0];
            } else {
                $result['filler_order_num'] = $obr->getField(3);
            }
        } else {
            $result['filler_order_num'] = "";
        }
        if (substr($obr->getField(4)[1], -1) == "=") {
            $result['name'] = $obr->getField(20)[1];
        } else {
            $result['name'] = $obr->getField(4)[1];
        }

        $result['status'] = $obr->getField(25);
        if ($obr->getField(7) != null) {
            $result['specimen_collection_date'] = substr($obr->getField(7), 4, 2) . '/' .
                substr($obr->getField(7), 6, 2) . '/' .
                substr($obr->getField(7), 0, 4) . '   ' .
                substr($obr->getField(7), 8, 2) . ':' .
                substr($obr->getField(7), 10, 2);
        } else {
            $result['specimen_collection_date'] = "";
        }
        if ($obr->getField(14) != null) {
            $result['specimen_received_date'] = substr($obr->getField(14), 4, 2) . '/' .
                substr($obr->getField(14), 6, 2) . '/' .
                substr($obr->getField(14), 0, 4) . '   ' .
                substr($obr->getField(14), 8, 2) . ':' .
                substr($obr->getField(14), 10, 2);
        } else {
            $result['specimen_received_date'] = "";
        }
        if ($obr->getField(22) != null) {
            $result['result_date'] = substr($obr->getField(22), 4, 2) . '/' .
                substr($obr->getField(22), 6, 2) . '/' .
                substr($obr->getField(22), 0, 4) . '   ' .
                substr($obr->getField(22), 8, 2) . ':' .
                substr($obr->getField(22), 10, 2);
        } else {
            $result['result_date'] = "";
        }
        if ($obr->getField(25) != null) {
            $result['result_status'] = $obr->getField(25);
        } else {
            $result['result_status'] = "";
        }

        // dd($result);
        return $result;
    }
    public function read_spm($spm)
    {
        // dd($spm);
        $result = array();
        if ($spm->getField(4) != null) {
            $result['specimen_code'] = $spm->getField(4)[0];
            $result['specimen_type'] = $spm->getField(4)[1];
        } else {
            $result['specimen_code'] = "";
            $result['specimen_type'] = "";
        }
        if ($spm->getField(18) != null) {
            $result['specimen_received_date'] = substr($spm->getField(18), 4, 2) . '/' .
                substr($spm->getField(18), 6, 2) . '/' .
                substr($spm->getField(18), 0, 4) . '   ' .
                substr($spm->getField(18), 8, 2) . ':' .
                substr($spm->getField(18), 10, 2);
        } else {
            $result['specimen_received_date'] = "";
        }
        if ($spm->getField(21) != null) {
            $result['specimen_reject_code'] = $spm->getField(21)[0];
            $result['specimen_reject_reason'] = $spm->getField(21)[1];
        } else {
            $result['specimen_reject_code'] = "";
            $result['specimen_reject_reason'] = "";
        }
        return $result;
    }
    public function hl7Encode($orderItem)
    {
        // dd($orderItem);
        $getVanderDetail = DB::table('vendor_accounts')->where('vendor', 'quest')->first();
        $getPatitentDetail = DB::table('users')->where('id', $orderItem->umd_patient_id)->first();
        $getPatitentCity = DB::table('cities')->where('id', $getPatitentDetail->city_id)->first();
        $getPatitentStates = DB::table('states')->where('id', $getPatitentDetail->state_id)->first();
        $getDoctorDetail = DB::table('users')->where('nip_number', $orderItem->npi)->first();

        $msg = new Message();

        //MSH Segment Start
        $fieldSeprater = "|";
        $encodinCharacterSet = "^~\&";
        $sendingApplication = $getVanderDetail->name;
        $sendingFacility = $getVanderDetail->number;
        $receivingApplication = 'PSC';
        $receivingFacility = 'STL';
        $dateTimeOfMessage = date("YmdHi", strtotime($orderItem->created_at));
        $triggerEvent = "O01";
        $messageType = "ORM";
        $messageControlId = "202104210930";
        $processingId = "T";
        $versionId = "2.3.1";

        // 2021 09 01 09 30

        $msh = new Segment('MSH');
        $msh->setField('1', $fieldSeprater);
        $msh->setField('2', $encodinCharacterSet);
        $msh->setField('3', $sendingApplication);
        $msh->setField('4', $sendingFacility);
        $msh->setField('5', $receivingApplication);
        $msh->setField('6', $receivingFacility);
        $msh->setField('7', $dateTimeOfMessage);
        $msh->setField('9', [$messageType, $triggerEvent]);
        $msh->setField('10', $messageControlId);
        $msh->setField('11', $processingId);
        $msh->setField('12', $versionId);
        $msg->addSegment($msh);
        //MSH Segment End
        // dd($msh->getFeild());
        //PID Segment Start
        $patientID = "1";
        $patientExternalID = $orderItem->umd_patient_id;
        // $setAlternatePatientID="201409010326sa";
        $patientFirstName = $getPatitentDetail->name;
        $patientLastName = $getPatitentDetail->last_name;
        //patientSex format 'F' 'M' 'A' 'N' 'O' 'U'

        $patientSex = ucfirst(substr($getPatitentDetail->gender, 0, 1));
            //dateofBirth_format date("Ymd")
        ;
        $patientDateOfBirth = date("Ymd", strtotime($getPatitentDetail->date_of_birth));
        $patientStreetAddress = $getPatitentDetail->office_address;
        $patientCity = $getPatitentCity->name;
        $patientState = $getPatitentStates->name;
        $patientZipCode = $getPatitentDetail->zip_code;
        $patient_area_or_city_code = $getPatitentCity->state_code;
        $patientPhoneNumber = $getPatitentDetail->phone_number;
        $patientSSN = '000-00-0000';

        // $patientInternalID=[];
        // $patientInternal_id = "3024";
        $patientInternal_id = $orderItem->umd_patient_id;
        $patientInternalID_authority = "MRN";

        //OBX Segment Start

        // agar multiple OBX segment set karni hai to OBX segment set karny k leye loop ka use karna hoga

        $pid = new Segment('PID');
        $pid->setField('1', $patientID);
        $pid->setField('2', $patientExternalID);
        $pid->setField('3', $patientInternal_id);
        $pid->setField('5', [$patientLastName, $patientFirstName]);
        $pid->setField('7', $patientDateOfBirth);
        $pid->setField('8', $patientSex);
        $pid->setField('11', [$patientStreetAddress, '', $patientCity, $patientState, $patientZipCode]);
        $pid->setField('13', $patientPhoneNumber);
        $pid->setField('19', $patientSSN);
        $msg->addSegment($pid);
        //PID Segment End

        //IN1 Segment Start
        $in1 = new Segment('IN1');

        $in1->setField('1', "1");
        $in1->setField('47', "C");

        $msg->addSegment($in1);
        //IN1 Segment End

        //ORC Segment Start

        $orcID = "NW";
        $orcplacerOrderNumber = $orderItem->order_id;
        $orcST_IdNumber = $getDoctorDetail->nip_number;
        $orcFirstName = $getDoctorDetail->name;
        $orcLastName = $getDoctorDetail->last_name;
        $orcAssigningAuthority = "NPI";
        $orcDate = date("YmdHis");

        $orderItem->names = json_decode($orderItem->names, true);
        $orderItem->orderedtestcode = json_decode($orderItem->orderedtestcode, true);
        $aoe_data = $orderItem->aoe;
        $aoes = json_decode($aoe_data, true);
        // dd($orderItem->orderedtestcode);
        //OBR Segment Start
        foreach ($orderItem->names as $key => $testname) {
            //ORC Segment Start
            $orc = new Segment('ORC');
            $orc->setField('1', $orcID);
            $orc->setField('2', $orcplacerOrderNumber);
            $orc->setField('9', $orcDate);
            $orc->setField('12', [$orcST_IdNumber, $orcFirstName, $orcLastName, '', '', '', '', '', $orcAssigningAuthority]);

            $msg->addSegment($orc);
            //ORC Segment End

            $obrID = $key + 1;
            $obrPlacerOrderNumber = $orderItem->order_id;
            $obrFillerOrderNumber = rand();
            $obrAlternativeIdentifier = $orderItem->orderedtestcode[$key]; //different for test
            $obrAlternativeText = $testname;
            //$obrDate format date("Ymd")
            $obrDate = date("YmdHis");
            $obrST_IdNumber = $getDoctorDetail->nip_number;
            $obrFirstName = $getDoctorDetail->name;
            $obrLastName = $getDoctorDetail->last_name;
            $fullname = $getDoctorDetail->name . ' ' . $getDoctorDetail->last_name;
            $obrAssigningAuthority = 'NPI';

            $obr = new Segment('OBR');

            $obr->setField('1', $obrID);
            $obr->setField('2', $obrPlacerOrderNumber);
            $obr->setField('3', $obrFillerOrderNumber);
            $obr->setField('4', ['', '', '', $obrAlternativeIdentifier, $obrAlternativeText]);
            $obr->setField('5', 'R');
            $obr->setField('6', $obrDate);
            // $obr->setField('7', $obrDate);
            $obr->setField('16', [$obrST_IdNumber, $obrFirstName, $obrLastName, $fullname, '', '', '', '', $obrAssigningAuthority]);
            $obr->setField('28', ['', '', '', '', '', 'R']);

            $msg->addSegment($obr);
            $test_code = $orderItem->orderedtestcode[$key];
            // dd($aoes);
            // dd($test_code);
            foreach ($aoes as $key => $value) {
                if ($key == $test_code) {
                    foreach ($value as $ques) {
                        $obxID = $key + 1;
                        $questionId = $ques['ques_id'];
                        $question = $ques['ques'];
                        $obxAlternativeText = $ques['ans'];
                        // $obxResultStatus = "P"; //Preliminary Result "P" mean

                        $obx = new Segment('OBX');

                        $obx->setField('1', $obxID);
                        // $obx->setField('2', 'ST');
                        $obx->setField('3', ['', '', '', $questionId, $question]); //question id
                        $obx->setField('5', $obxAlternativeText); //answer
                        // $obx->setField('11', $obxResultStatus);

                        $msg->addSegment($obx);
                    }
                }
            }
        }

        //OBR Segment End

        //

        //OBX Segment End

        //DG1 Segment Start

        // $dg1ID = "1";
        // $dg1CodingMethod = "ICD";
        // $dg1Code = $orderItem->icd_diagnosis_code;
        // $digDesc = $orderItem->diagnosis_desc;

        // $dg1 = new Segment('DG1');
        // $dg1->setField('1', $dg1ID);
        // $dg1->setField('2', $dg1CodingMethod);
        // $dg1->setField('3', $dg1Code);
        // $dg1->setField('4', $digDesc);
        // $msg->addSegment($dg1);
        // var_dump((array)$msg);die;
        $str_lf = $msg->toString(true);
        // $trim=trim($str_lf);
        $str = str_replace("\n", "\r", $str_lf);
        // $str=preg_replace("/\r/", "", $str);
        // $str=stripslashes($str_lf);
        // $str=ltrim($str_lf,'\"');
        // dd($str);
        $base64OfHl7Code = base64_encode($str);
        // dd($orderItem);
        $quest_order = QuestRequest::create([
            'order_id' => $orderItem->order_id,
            'patient_id' => $orderItem->umd_patient_id,
            'documentTypes' => 'REQ',
            'orderHL7' => $base64OfHl7Code,
            'hl7_payload' => $str,
        ]);
        $arr = [
            'documentTypes' => ['REQ'],
            'orderHl7' => $base64OfHl7Code,
        ];
        $json_req = json_encode($arr);

        // Send Order to Quest
        $curl = curl_init();
        if (env('APP_TYPE') == 'production') {
            $url = 'https://hubservices.quanum.com/rest/orders/v1/document';
            $token = env('PRODUCTION_QUEST_TOKEN');
        } else {
            $url = 'https://certhubservices.quanum.com/rest/orders/v1/document';
            $token = env('QUEST_TOKEN');
        }
        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $json_req,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        // Save response(base64 requisitions file) from Quest Order to database
        $quest_order->responseMessage = $response;
        // (array)json_decode($response)['orderSupportDocuments']
        // dd(json_decode($response));
        if (((array)json_decode($response))['orderSupportDocuments'] != null) {
            $document = ((array)(((array)json_decode($response))['orderSupportDocuments'][0]))['documentData'];
            $file_name = $this->decodeAndSaveRequisition($document);
            $quest_order->requisition_file = $file_name;
        } else {
            $quest_order->requisition_file = '';
        }
        $quest_order->save();
        // $this->sendRequisitionMail($quest_order);
    }
    public function new_hl7Encode($orderItem)
    {
        // dd($orderItem);
        $getVanderDetail = DB::table('vendor_accounts')->where('vendor', 'quest')->first();
        $getPatitentDetail = DB::table('users')->where('id', $orderItem->umd_patient_id)->first();
        $getPatitentCity = DB::table('cities')->where('id', $getPatitentDetail->city_id)->first();
        $getPatitentStates = DB::table('states')->where('id', $getPatitentDetail->state_id)->first();
        $getDoctorDetail = DB::table('users')->where('nip_number', $orderItem->npi)->first();

        $msg = new Message();

        //MSH Segment Start
        $fieldSeprater = "|";
        $encodinCharacterSet = "^~\&";
        $sendingApplication = $getVanderDetail->name;
        $sendingFacility = $getVanderDetail->number;
        $receivingApplication = 'PSC';
        $receivingFacility = 'STL';
        $dateTimeOfMessage = date("YmdHi", strtotime($orderItem->created_at));
        $triggerEvent = "O01";
        $messageType = "ORM";
        $messageControlId = "202104210930";
        if (env('APP_TYPE') == 'production') {
        $processingId = "P";
        }else{
        $processingId = "T";

        }
        $versionId = "2.3.1";

        $msh = new Segment('MSH');
        $msh->setField('1', $fieldSeprater);
        $msh->setField('2', $encodinCharacterSet);
        $msh->setField('3', $sendingApplication);
        $msh->setField('4', $sendingFacility);
        $msh->setField('5', $receivingApplication);
        $msh->setField('6', $receivingFacility);
        $msh->setField('7', $dateTimeOfMessage);
        $msh->setField('9', [$messageType, $triggerEvent]);
        $msh->setField('10', $messageControlId);
        $msh->setField('11', $processingId);
        $msh->setField('12', $versionId);
        $msg->addSegment($msh);
        //MSH Segment End



        //PID Segment Start
        $patientID = "1";
        $patientExternalID = $orderItem->umd_patient_id;
        $patientFirstName = $getPatitentDetail->name;
        $patientLastName = $getPatitentDetail->last_name;
        $patientSex = ucfirst(substr($getPatitentDetail->gender, 0, 1));
        $patientDateOfBirth = date("Ymd", strtotime($getPatitentDetail->date_of_birth));
        $patientStreetAddress = $getPatitentDetail->office_address;
        $patientCity = $getPatitentCity->name;
        $patientState = $getPatitentStates->name;
        $patientZipCode = $getPatitentDetail->zip_code;
        $patient_area_or_city_code = $getPatitentCity->state_code;
        $patientPhoneNumber = $getPatitentDetail->phone_number;
        $patientSSN = '000-00-0000';
        $patientInternal_id = $orderItem->umd_patient_id;
        $patientInternalID_authority = "MRN";

        $pid = new Segment('PID');
        $pid->setField('1', $patientID);
        $pid->setField('2', $patientExternalID);
        $pid->setField('3', $patientInternal_id);
        $pid->setField('5', [$patientLastName, $patientFirstName]);
        $pid->setField('7', $patientDateOfBirth);
        $pid->setField('8', $patientSex);
        $pid->setField('11', [$patientStreetAddress, '', $patientCity, $patientState, $patientZipCode]);
        $pid->setField('13', $patientPhoneNumber);
        $pid->setField('19', $patientSSN);
        $msg->addSegment($pid);
        //PID Segment End

        //IN1 Segment Start
        $in1 = new Segment('IN1');
        $in1->setField('1', "1");
        $in1->setField('47', "C");
        $msg->addSegment($in1);
        //IN1 Segment End

        //ORC Segment Start
        $orcID = "NW";
        $orcplacerOrderNumber = $orderItem->order_id;
        $orcST_IdNumber = $getDoctorDetail->nip_number;
        $orcFirstName = $getDoctorDetail->name;
        $orcLastName = $getDoctorDetail->last_name;
        $orcAssigningAuthority = "NPI";
        $orcDate = date("YmdHis", strtotime($orderItem->created_at));




        // agar multiple OBX segment set karni hai to OBX segment set karny k leye loop ka use karna hoga
        $testData = json_decode($orderItem->names);
        // dd($testData);
        foreach ($testData as $test) {
            $orc = new Segment('ORC');
            $orc->setField('1', $orcID);
            $orc->setField('2', $orcplacerOrderNumber);
            $orc->setField('9', $orcDate);
            $orc->setField('12', [$orcST_IdNumber, $orcFirstName, $orcLastName, '', '', '', '', '', $orcAssigningAuthority]);
            $msg->addSegment($orc);
            //ORC Segment End

            //OBR Segment Start
            $obrID = rand(1, 100);
            $obrPlacerOrderNumber = $orderItem->order_id;
            $obrFillerOrderNumber = rand();
            $obrAlternativeIdentifier = $test->testCode; //different for test
            $obrAlternativeText = $test->testName;
            $obrDate = date("YmdHis", strtotime($orderItem->created_at));
            $obrST_IdNumber = $getDoctorDetail->nip_number;
            $obrFirstName = $getDoctorDetail->name;
            $obrLastName = $getDoctorDetail->last_name;
            $fullname = $getDoctorDetail->name . ' ' . $getDoctorDetail->last_name;
            $obrAssigningAuthority = 'NPI';


            $obr = new Segment('OBR');
            $obr->setField('1', $obrID);
            $obr->setField('2', $obrPlacerOrderNumber);
            $obr->setField('3', $obrFillerOrderNumber);
            $obr->setField('4', ['', '', '', $obrAlternativeIdentifier, $obrAlternativeText]);
            $obr->setField('5', 'R');
            $obr->setField('6', $obrDate);
            $obr->setField('16', [$obrST_IdNumber, $obrFirstName, $obrLastName, $fullname, '', '', '', '', $obrAssigningAuthority]);

            $obr->setField('28', ['', '', '', '', '', 'DR']);

            $msg->addSegment($obr);
            //OBR Segment End


            //OBX Segment Start
            $aoes = $test->aoes;
            if ($aoes != null || $aoes != '') {
                foreach ($aoes as $value) {
                    $obxID = rand(1, 3999);
                    $questionId = $value->ques_id;
                    $question = $value->ques;
                    $obxAlternativeText = $value->ans;
                    $obx = new Segment('OBX');
                    $obx->setField('1', $obxID);
                    $obx->setField('3', ['', '', '', $questionId, $question]); //question id
                    $obx->setField('5', $obxAlternativeText); //answer
                    $msg->addSegment($obx);
                }
            }
        }
        //OBX Segment End


        $str_lf = $msg->toString(true);
        $str = str_replace("\n", "\r", $str_lf);
        $base64OfHl7Code = base64_encode($str);
        $quest_order = QuestRequest::create([
            'order_id' => $orderItem->order_id,
            'patient_id' => $orderItem->umd_patient_id,
            'documentTypes' => 'REQ',
            'orderHL7' => $base64OfHl7Code,
            'hl7_payload' => $str,
            'quest_lab_id'=> $orderItem->id,
        ]);
        $arr = [
            'documentTypes' => ['REQ'],
            'orderHl7' => $base64OfHl7Code,
        ];
        $json_req = json_encode($arr);

        // Send Order to Quest
        $curl = curl_init();
        if (env('APP_TYPE') == 'production') {
            $url = 'https://hubservices.quanum.com/rest/orders/v1/document';
            $token = env('PRODUCTION_QUEST_TOKEN');
        } else {
            $url = 'https://certhubservices.quanum.com/rest/orders/v1/document';
            $token = env('QUEST_TOKEN');
        }
        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $json_req,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        // Save response(base64 requisitions file) from Quest Order to database
        $quest_order->responseMessage = $response;
        if (((array)json_decode($response))['orderSupportDocuments'] != null) {
            $document = ((array)(((array)json_decode($response))['orderSupportDocuments'][0]))['documentData'];
            $file_name = $this->decodeAndSaveRequisition($document);
            $quest_order->requisition_file = $file_name;
        } else {
            $quest_order->requisition_file = '';
        }
        $quest_order->save();
        $quest_order->zip_code = $orderItem->zip_code;
        $this->sendRequisitionMail($quest_order);
    }
    public function decodeAndSaveRequisition($response_base64)
    {
        $base64_decode_of_doc = base64_decode($response_base64);
        $pdf_decoded = base64_decode($base64_decode_of_doc);
        $timestamp = time();
        $file_name = 'lab_requisitions/' . $timestamp . '.pdf';
        $status = \Storage::disk('s3')->put($file_name, $pdf_decoded);
        if ($status)
            return $file_name;
        else return $status;

        // $timestamp=time();
        // //Write data back to pdf file
        // if($_SERVER['SERVER_NAME']=='umbrellamd-video.com'){
        //         $pdf = fopen('/var/www/html/umbrellamd/public/uploads/requisitions/' . $timestamp . '.pdf', 'w');
        //     }else if($_SERVER['SERVER_NAME']=='demo.umbrellamd-video.com'){
        //         $pdf = fopen('/var/www/html/umbrellamd-demo/public/uploads/requisitions/' . $timestamp . '.pdf', 'w');
        //     }else if($_SERVER['SERVER_NAME']=='www.umbrellamd.com'){
        //         $pdf = fopen('/var/www/html/umbrellamd8.0/public/uploads/requisitions/' . $timestamp . '.pdf', 'w');
        //     }else{
        //         $pdf = fopen('uploads/lab_reports/' . $timestamp . '.pdf', 'w');
        //     }
        // fwrite ($pdf,$pdf_decoded);
        // //close output file
        // fclose ($pdf);

    }
    public function sendRequisitionMail($quest_order)
    {
        $patient = User::find($quest_order->patient_id);
        $quest_order->patient = $patient;
        $quest_order->locations = QuestDataSites::where('ZIP_CD', 'like', '%' . $quest_order->zip_code . '%')
            // ->groupBy('ZIP_CD')
            ->limit(3)
            ->get();
        // dd($quest_order->locations);
        Mail::to($patient->email)->send(new RequisitionFile($quest_order));
        Log::channel('questResults')->info('Sent Requisition file to ' . $patient->email);

        return true;
        // Mail::to('shahzaib.webstars360@gmail.com')->send(new RequisitionFile($quest_order));
        // // Mail::to('suunnoo@gmail.com')->send(new RequisitionFile($quest_order));
        // Mail::to('ahadi@umbrellamd.com')->send(new RequisitionFile($quest_order));
        // Mail::to('rhunar007@gmail.com')->send(new RequisitionFile($quest_order));
        // Mail::to('mark.a.fore@questdiagnostics.com')->send(new RequisitionFile($quest_order));
        // echo 'done';
    }
    public function createACKmessage($control_id)
    {
        $getVanderDetail = DB::table('vendor_accounts')->where('vendor', 'quest')->first();

        $msg = new Message();

        //MSH Segment Start
        $fieldSeprater = "|";
        $encodinCharacterSet = "^~\&";
        $sendingApplication = $getVanderDetail->name;
        $sendingFacility = $getVanderDetail->number;
        $receivingApplication = 'PSC';
        $receivingFacility = 'STL';
        $dateTimeOfMessage = date("YmdHi", time());
        // $triggerEvent = "O01";
        $messageType = "ACK";
        // $messageControlId = "202104210930";
        $processingId = "D";
        $versionId = "2.3.1";

        // 2021 09 01 09 30

        $msh = new Segment('MSH');
        $msh->setField('1', $fieldSeprater);
        $msh->setField('2', $encodinCharacterSet);
        $msh->setField('3', $sendingApplication);
        $msh->setField('4', $sendingFacility);
        $msh->setField('5', $receivingApplication);
        $msh->setField('6', $receivingFacility);
        $msh->setField('7', $dateTimeOfMessage);
        $msh->setField('9', $messageType);
        $msh->setField('10', $dateTimeOfMessage);
        $msh->setField('11', $processingId);
        $msh->setField('12', $versionId);
        $msg->addSegment($msh);
        $msa = new Segment('MSA');
        $msa->setField('1', 'CA');
        $msa->setField('2', $control_id);
        $msg->addSegment($msa);
        // dd($msg->toString(true));
        // $this->sendACKtoQuest()
        return $msg->toString(true);
    }
    public function sendReportMail($result, $patient_id, $doc_id)
    {
        try {
            if ($patient_id != '') {
                $patient = User::find($patient_id);
                // Mail::to($patient->email)->send(new ReportFile($result));
                Log::channel('questResults')->info('Sent Result to ' . $patient->email);
            }
            if ($doc_id != '') {
                $doc = User::find($doc_id);
                // Mail::to($doc->email)->send(new ReportFile($result));
                Log::channel('questResults')->info('Sent Result to ' . $doc->email);
            }
        } catch (Exception $e) {
        }
    }
}
