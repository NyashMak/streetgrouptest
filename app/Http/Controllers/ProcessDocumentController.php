<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\HomeOwnersImport;

class ProcessDocumentController extends Controller
{
    private $titles = ['Mr', 'Mrs', 'Miss', 'Ms', 'Dr', 'Prof']; // We can keep adding titles
    private function processFileData($data, $titles)
    {
        $people = [];

        foreach ($data as $sheet) {
            foreach ($sheet as $entry) {
                $homeowners = preg_split('/\s*(?:\s+and\s+|\s*&\s*)\s*/', $entry['homeowner']);
                
                foreach ($homeowners as $homeowner) {
                    $parts = preg_split('/\s+/', $homeowner, -1, PREG_SPLIT_NO_EMPTY);
                    
                    // Check if the last name is a title, if so, treat it as part of the title
                    $title = in_array(end($parts), $titles) ? array_pop($parts) : $parts[0];
                    $firstName = null;
                    $initial = null;
                    $lastName = null;
                     
                    if (count($parts) === 3 && strlen($parts[1]) <= 2) {// 3 Part name without firstname but with Initial
                        if (substr($parts[1], -1) === '.') {
                            $initial = substr($parts[1], 0, 1);
                        } else {
                            $initial = $parts[1];
                        }
                        $firstName = null;
                        $lastName = end($parts);
                    } elseif (count($parts) === 3) {// 3 part with firstname, no Initial
                        $firstName = $parts[1];
                        $lastName = end($parts);
                    } elseif (count($parts) === 2 && strlen($parts[1]) === 2 && substr($parts[1], -1) === '.') {
                        $initial = $parts[1];
                        $lastName = end($parts);
                    } elseif (count($parts) === 1) {
                        $lastName = $parts[0];
                    }
                    
                    if ($lastName === null) {// $lastName can't be null, use shared last_name in the entry
                        $lastOwner = end($homeowners);
                        $lastOwnerNames = explode(' ', $lastOwner);
                        $lastName = end($lastOwnerNames);
                    }
                    
                    $people[] = [
                        'title' => $title,
                        'first_name' => $firstName,
                        'initial' => $initial,
                        'last_name' => $lastName,
                    ];
                }
            }
        }
        return $people;
    }
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx|max:2048', // We can adjust max file size as needed
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $file->storeAs('uploads', $fileName);

        $file = storage_path('app/uploads/'.$fileName);

        $data = Excel::toArray(new HomeOwnersImport, $file);

        $people = $this->processFileData($data, $this->titles);

        return view("index", compact('people'));
    }
}
