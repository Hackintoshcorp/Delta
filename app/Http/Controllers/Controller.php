<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Models\Controller_data;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function account(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore(auth()->user()->id), // Exclude the current user by ID
            ],
        ]);
        
        $user = User::find(auth()->user()->id);
        if ($user) {
            
            $user->name = $request->input('name');
            $user->surname = $request->input('surname');
            $user->email = $request->input('email');
            $user->save(); 
            return redirect()->back();
        }
    }

    public function adddevice(Request $request)
{
    $this->validate($request, [
        'device' => 'required|string|max:255',
        'uuid' => 'required|string|max:255',
    ]);
    
    $users = User::whereRaw("MD5(email) = ?", [$request->input('device')])->get();

    if ($users->count() > 0) {
        // At least one user found
        $userID = $users->pluck('id')[0];

        $controller = Controller_data::find($userID);
        
        if ($controller) {
            // Retrieve the existing devices as an array
            $devicesList = json_decode($controller->devices_list, true) ?? [];
        
            // Add the new uuid to the array
            $devicesList[] = $request->input('uuid');
        
            // Update the devices_list column with the updated JSON array
            $controller->devices_list = json_encode($devicesList);
            $controller->save();
        } else {
            // Create a new user record with the uuid in a JSON array
            $controller = Controller_data::create([
                'user_id' => $userID,
                'devices_list' => json_encode([$request->input('uuid')]),
                'history' => '',
            ]);
        }
        
        return redirect()->back();
    } else {
        // No user found
        return response()->json(['message' => 'User not found'], 404);
    }
}


public function indexPage()
{
    $currentUserId = auth()->user()->id;
    $controller = Controller_data::where('user_id', $currentUserId)->first();

    if ($controller) {
        $devicesList = json_decode($controller->devices_list);
    } else {
        $devicesList = [];
    }

    if (auth()->check()) {
        return view('index')
            ->with('devicesList', $devicesList);
            //->with('otherData', $otherData)
            //->with('moreData', $moreData);
    } else {
        return redirect()->route('login');
    }
}

public function removeDevice(Request $request)
{
    $deviceToRemove = $request->input('device');

    $currentUserId = auth()->user()->id;
    $controller = Controller_data::where('user_id', $currentUserId)->first();

    if ($controller) {
        $devicesList = json_decode($controller->devices_list, true);

        $index = array_search($deviceToRemove, $devicesList);
        if ($index !== false) {
            array_splice($devicesList, $index, 1);
            $controller->devices_list = json_encode($devicesList);
            $controller->save();
        }
    }
    return response()->json(['message' => 'Device removed successfully']);
}




}
