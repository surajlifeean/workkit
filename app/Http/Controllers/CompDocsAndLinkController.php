<?php

namespace App\Http\Controllers;

use App\Models\CompanyDocsAndLinks;
use Illuminate\Http\Request;

class CompDocsAndLinkController extends Controller
{
    public function index()
    {

        $linksDocs = CompanyDocsAndLinks::where('employee_id', auth()->user()->id)
            ->join('employees', 'company_docs_and_links.employee_id', '=', 'employees.id')
            ->whereNull('company_docs_and_links.deleted_at')
            ->select('company_docs_and_links.*', 'employees.phone', 'employees.username')
            ->orderBy('company_docs_and_links.created_at', 'desc')
            ->get();

        return view('links_docs.index', compact('linksDocs'));
    }

    public function store(Request $request){


        $validatedData = $request->validate([
            'name' => 'required|string|max:255', 
            'type' => 'required|in:doc,link',   
            'company_id' => 'required|integer',  
            'upload' => 'max:2048', 
            'link' => 'url',                  
        ]);
        if ($request->hasFile('upload')) {
            $image = $request->file('upload');
            $filename = time().'.'.$image->extension();  
            $image->move(public_path('/comp_docs'), $filename);
        
        
            return response()->json('File uploaded successfully.');
        }
        
        
        // return response()->json($request);
   

    }
}
