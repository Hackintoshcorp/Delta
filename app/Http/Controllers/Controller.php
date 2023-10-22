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
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function account(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(auth()->id())],
        ]);

        auth()->user()->update($request->only(['name', 'surname', 'email']));
        return redirect()->back();
    }

    public function addDevice(Request $request)
    {
        $this->validate($request, ['device' => 'required|string|max:255', 'uuid' => 'required|string|max:255']);

        $users = User::whereRaw("MD5(email) = ?", [$request->input('device')])->get();

        if ($users->isEmpty()) {
            return Response::json(['message' => 'User not found'], 404);
        }

        $userID = $users->pluck('id')->first();
        $controller = Controller_data::firstOrNew(['user_id' => $userID]);

        $devicesList = json_decode($controller->devices_list, true) ?: [];

        if (in_array($request->input('uuid'), $devicesList)) {
            return Response::json(['message' => 'Device already added'], 400);
        }

        $devicesList[] = $request->input('uuid');
        $controller->devices_list = json_encode($devicesList);
        $controller->save();

        return redirect()->back();
    }

    public function indexPage()
    {
        $currentUserId = auth()->id();
        $controller = Controller_data::where('user_id', $currentUserId)->first();
        $devicesList = $controller ? json_decode($controller->devices_list) : [];
        $history = $controller ? json_decode($controller->history, true) : [];
        $operationsToRetrieve = ["scanOne", "scanFull", "optimize", "regenKeys"];
        $lastDates = [];

        foreach ($operationsToRetrieve as $operation) {
            $lastDate = null;
            foreach ($history as $entry) {
                if ($entry['execute'] === $operation) {
                    $lastDate = $entry['date'];
                }
            }
            $lastDates[$operation] = $lastDate;
        }

        return auth()->check()
            ? view('index', compact('devicesList', 'lastDates'))
            : redirect()->route('login');
    }

    public function removeDevice(Request $request)
    {
        $deviceToRemove = $request->input('device');
        $currentUserId = auth()->id();
        $controller = Controller_data::where('user_id', $currentUserId)->first();

        if ($controller) {
            $devicesList = json_decode($controller->devices_list, true) ?? [];
            $index = array_search($deviceToRemove, $devicesList);

            if ($index !== false) {
                array_splice($devicesList, $index, 1);
                $controller->devices_list = json_encode($devicesList);
                $controller->save();
            }
        }

        return Response::json(['message' => 'Device removed successfully']);
    }

    public function updateHistory(Request $request)
    {
        $currentUserId = auth()->id();
        $controller = Controller_data::where('user_id', $currentUserId)->first();
        $execute = $request->input('execute');
        $date = $request->input('date');

        if ($controller) {
            $history = json_decode($controller->history, true) ?: [];
            $recordExists = collect($history)->contains(fn($entry) => $entry['execute'] === $execute && $entry['date'] === $date);

            if (!$recordExists) {
                $history[] = ['execute' => $execute, 'date' => $date];
                $controller->history = json_encode($history);
                $controller->save();
            }
        }

        return Response::json(['message' => 'History updated successfully']);
    }
}