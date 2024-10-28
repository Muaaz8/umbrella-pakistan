<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase\Factory;
use Storage;
use Config;

class Helper extends Model
{
    public static function get_date_with_format_by_user($date)
    {
        return date('M, dS Y', strtotime($date));
    }
    public static function get_date_with_format($date)
    {
        return date('M, dS Y', strtotime($date));
    }
    public static function get_time_with_format($time)
    {
        return date('h:i:s a', strtotime($time));
    }
    public static function get_name($id)
    {
        $user = User::find($id);
        return !empty($user) ? $user['name'] . " " . $user['last_name'] : 'N/A';
    }
    public static function get_files_url($file)
    {
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $bucket = Config::get('filesystems.disks.s3.bucket');
        $command = $client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => $file  // file name in s3 bucket which you want to access
        ]);

        $request = $client->createPresignedRequest($command, '+20 minutes');

        // Get the actual presigned-url
        return $presignedUrl = (string)$request->getUri();
        // return 'http://umb-files.umbrellamd-video.com/';
    }
    public static function check_bucket_files_url($file)
    {
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $bucket = Config::get('filesystems.disks.s3.bucket');
        $res = $client->doesObjectExist($bucket, $file);
        if ($res) {
            $data = self::get_files_url($file);
            return $data;
        } else {
            return env('APP_URL') . '/assets/images/user.png';
        }
    }


    // firebase(user_id,type,type_id,data)
    public static function firebase($user_id,$type,$type_id,$data)
    {
        $app = env('APP_TYPE');
        // Get a reference to the database
        $firebase = (new Factory)
            ->withServiceAccount(base_path('firebase_credentials.json'))
            ->withDatabaseUri('https://uhcs-d2634-default-rtdb.asia-southeast1.firebasedatabase.app');

        // Get a reference to the "users" node
        $database = $firebase->createDatabase();
        $blogRef = $database->getReference($app);
        $blogRef->getChild($user_id)->getChild($type)->getChild($type_id)->set($data);
        return true;
    }
    // firebase(type,type_id,data)
    public static function firebaseOnlineDoctor($type,$type_id,$data)
    {
        $app = env('APP_TYPE')."_".$type;
        // Get a reference to the database
        $firebase = (new Factory)
            ->withServiceAccount(base_path('firebase_credentials.json'))
            ->withDatabaseUri('https://uhcs-d2634-default-rtdb.asia-southeast1.firebasedatabase.app');

        // Get a reference to the "users" node
        $database = $firebase->createDatabase();
        $blogRef = $database->getReference($app);
        $blogRef->getChild($type_id)->set($data);
        return true;
    }
}
