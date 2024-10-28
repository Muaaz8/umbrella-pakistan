<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Factory;

class FirebaseController extends Controller
{
    public function index()
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path('firebase_credentials.json'))
            ->withDatabaseUri('https://uhcs-d2634-default-rtdb.asia-southeast1.firebasedatabase.app');

        $database = $firebase->createDatabase();

        $blog = $database
            ->getReference('blog');

        echo '<pre>';
        print_r($blog->getvalue());
        echo '</pre>';
    }

    public function store()
    {
        // Get a reference to the database
        $firebase = (new Factory)
            ->withServiceAccount(base_path('firebase_credentials.json'))
            ->withDatabaseUri('https://uhcs-d2634-default-rtdb.asia-southeast1.firebasedatabase.app');

        // Get a reference to the "users" node
        $database = $firebase->createDatabase();

        $blogRef = $database->getReference('blog');

        // Create a new user object
        $newUser = [
            'name' => 'Haris Unar',
            'email' => 'harisUnar@example.com',
            'age' => 23,
            'gender' => 'male',
        ];

        // Push the new user object to the "users" node
        // $newUserRef = $usersRef->push($newUser);

        $newUserId = 'user123';

        // Add the new user object to the "blog" node with the custom key
        $blogRef->getChild($newUserId)->set($newUser);

        // Get the key of the new user object
        // $newUserId = $newUserRef->getKey();

        // Output the new user ID
        echo 'New user ID: ' . $newUserId;
    }
}
