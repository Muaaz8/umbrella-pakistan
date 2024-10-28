<?php

namespace App\Http\Controllers\Api;

use App\AgoraAynalatics;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgoraController extends Controller
{

    public function aquire(Request $request)
    {

        $AppID = env('AGORA_APP_ID');
        $CustomerID = env('AGORA_CUSTOMER_ID');
        $CustomerSecret = env('AGORA_CUSTOMER_SECRET');
        $AuthSecret = base64_encode("$CustomerID:$CustomerSecret");

        $userID = $request->userid;
        $Channel = $request->channel;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.agora.io/v1/apps/$AppID/cloud_recording/acquire",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"cname\": \"$Channel\",\n  \"uid\": \"$userID\",\n  \"clientRequest\":{\n  }\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Basic $AuthSecret",
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $ee = json_decode($response);

        $this->start($ee->resourceId, $Channel, $AppID, $userID, $AuthSecret);
    }
    public function start($resID, $channel, $AppID, $userID, $AuthSecret)
    {
        $S3Region = env('S3_REGION');
        $S3Bucket = env('S3_BUCKET');
        $S3AccessKey = env('S3_ACCESS_KEY');
        $S3SecretKey = env('S3_SECRET_KEY');
        $curl = curl_init();
        // Receive ResourceID from client
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.agora.io/v1/apps/$AppID/cloud_recording/resourceid/$resID/mode/mix/start",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
            "cname":"' . $channel . '",
            "uid":"' . $userID . '",
            "clientRequest":{
                "recordingConfig":{
                    "maxIdleTime":120,
                    "streamTypes":2,
                    "audioProfile":1,
                    "channelType":1,
                    "videoStreamType":0,
                    "transcodingConfig":{
                    "width":720,
                    "height":640,
                    "fps":30,
                    "bitrate":2000,
                    "mixedVideoLayout":1,
                    "maxResolutionUid":"1"
                    }
                },
                "recordingFileConfig": {
                    "avFileType": [
                        "mp4",
                        "hls"
                    ]
            },
                "storageConfig":{
                    "vendor":1,
                    "region":' . $S3Region . ',
                    "bucket":"' . $S3Bucket . '",
                    "accessKey":"' . $S3AccessKey . '",
                    "secretKey":"' . $S3SecretKey . '",
                    "fileNamePrefix": [
                        "agoratest"
                    ]
                }
            }
        }',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Basic $AuthSecret",
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        $resourceId = $data->resourceId;
        $sid = $data->sid;

        $input['resID'] = $resourceId;
        $input['sID'] = $sid;
        $input['channel'] = $channel;
        $input['userID'] = $userID;
        AgoraAynalatics::updateOrCreate($input);

    }

    public function stop(Request $request)
    {
        $res = AgoraAynalatics::where('channel', $request->channel)->first();

        $AppID = env('AGORA_APP_ID');
        $CustomerID = env('AGORA_CUSTOMER_ID');
        $CustomerSecret = env('AGORA_CUSTOMER_SECRET');
        $AuthSecret = base64_encode("$CustomerID:$CustomerSecret");

        $curl = curl_init();

        // Receive ResourceID and sid from client

        $ResourceID = $res->resID;
        $sid = $res->sID;
        $Channel = $request->channel;
        $userID = $res->userID;

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.agora.io/v1/apps/$AppID/cloud_recording/resourceid/$ResourceID/sid/$sid/mode/mix/stop",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"cname\": \"$Channel\",\n  \"uid\": \"$userID\",\n  \"clientRequest\":{\n  }\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json;charset=utf-8",
                "Authorization: Basic $AuthSecret",
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        if ($response != null) {
            $res = json_decode($response);
            if (isset($res->serverResponse)) {
                $server_response = $res->serverResponse;
                if (isset($server_response->fileList[0]->fileName)) {
                    $val = $server_response->fileList[0]->fileName;
                    AgoraAynalatics::where('channel', $request->channel)->update(['video_link' => $val]);
                    $this->stop_patient($request->channel);
                } else {
                    return $response;
                }
            } else {
                return $response;
            }
        }
    }
    public function stop_patient($channel)
    {
        $res = AgoraAynalatics::where('channel', $channel)->first();

        $AppID = env('AGORA_APP_ID');
        $CustomerID = env('AGORA_CUSTOMER_ID');
        $CustomerSecret = env('AGORA_CUSTOMER_SECRET');
        $AuthSecret = base64_encode("$CustomerID:$CustomerSecret");

        $curl = curl_init();

        // Receive ResourceID and sid from client

        $ResourceID = $res->resID;
        $sid = $res->sID;
        $Channel = $channel;
        $userID = $res->secUserID;

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.agora.io/v1/apps/$AppID/cloud_recording/resourceid/$ResourceID/sid/$sid/mode/mix/stop",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"cname\": \"$Channel\",\n  \"uid\": \"$userID\",\n  \"clientRequest\":{\n  }\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json;charset=utf-8",
                "Authorization: Basic $AuthSecret",
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if ($response != null) {
            $res = json_decode($response);
            if (isset($res->serverResponse)) {
                $server_response = $res->serverResponse;
                if (isset($server_response->fileList[0]->fileName)) {
                    $val = $server_response->fileList[0]->fileName;
                    AgoraAynalatics::where('channel', $channel)->update(['video_link' => $val]);
                    //  $this->stop_patient($request->channel);
                } else {
                    return 'ok';
                }
            } else {
                return 'ok';
            }
        }
        return 'ok';
    }
    public function getAgoraAynalatics(Request $request)
    {
        $data = AgoraAynalatics::where('channel', $request->channel)->first();
        if ($data != null) {
            return response()->json($data);
        } else {
            return 0;
        }
    }
}
