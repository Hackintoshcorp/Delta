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
                Rule::unique('users')->ignore(auth()->user()->id),
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
            $userID = $users->pluck('id')[0];
            $controller = Controller_data::find($userID);

            if ($controller) {
                $devicesList = json_decode($controller->devices_list, true) ?? [];
                $devicesList[] = $request->input('uuid');
                $controller->devices_list = json_encode($devicesList);
                $controller->save();
            } else {
                $controller = Controller_data::create([
                    'user_id' => $userID,
                    'devices_list' => json_encode([$request->input('uuid')]),
                    'history' => '',
                ]);
            }
            return redirect()->back();
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function indexPage()
    {
        $currentUserId = auth()->user()->id;
        $controller = Controller_data::where('user_id', $currentUserId)->first();

        if ($controller) {
            $devicesList = json_decode($controller->devices_list);
            $history = json_decode($controller->history, true) ?? [];
            $lastDates = [];
            $operationsToRetrieve = ["scanOne", "scanFull", "optimize", "regenKeys"];

            foreach ($operationsToRetrieve as $operation) {
                $lastDate = null;
                foreach ($history as $entry) {
                    if ($entry['execute'] === $operation) {
                        $lastDate = $entry['date'];
                    }
                }
                $lastDates[$operation] = $lastDate;
            }
        } else {
            $devicesList = [];
            $lastDates = [];
        }

        if (auth()->check()) {
            return view('index')
                ->with('devicesList', $devicesList)
                ->with('lastDates', $lastDates);
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

    public function updateHistory(Request $request)
    {
        $currentUserId = auth()->user()->id;
        $controller = Controller_data::where('user_id', $currentUserId)->first();
        $execute = $request->input('execute');
        $date = $request->input('date');

        if ($controller) {
            $history = json_decode($controller->history, true) ?? [];
            $recordExists = false;
            foreach ($history as &$entry) {
                if ($entry['execute'] === $execute && $entry['date'] === $date) {
                    $recordExists = true;
                    break;
                }
            }
            if (!$recordExists) {
                $history[] = ['execute' => $execute, 'date' => $date];
                $controller->history = json_encode($history);
                $controller->save();
            }
        }
        return response()->json(['message' => 'History updated successfully']);
    }
}
