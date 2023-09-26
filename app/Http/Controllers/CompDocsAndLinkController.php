<?php

namespace App\Http\Controllers;

use App\Models\CompanyDocsAndLinks;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompDocsAndLinkController extends Controller
{
    public function index()
    {
        $linkDocs = null;

        if (auth()->user()->role_users_id == 4 || auth()->user()->role_users_id == 1)
            $linksDocs = CompanyDocsAndLinks::join('employees', 'company_docs_and_links.employee_id', '=', 'employees.id')
                ->whereNull('company_docs_and_links.deleted_at')
                ->select('company_docs_and_links.*', 'employees.phone', 'employees.username')
                ->orderBy('company_docs_and_links.created_at', 'desc')
                ->get();
        else {
            $linksDocs = CompanyDocsAndLinks::where('employee_id', auth()->user()->id)
                ->join('employees', 'company_docs_and_links.employee_id', '=', 'employees.id')
                ->whereNull('company_docs_and_links.deleted_at')
                ->select('company_docs_and_links.*', 'employees.phone', 'employees.username')
                ->orderBy('company_docs_and_links.created_at', 'desc')
                ->get();
        }

        return view('links_docs.index', compact('linksDocs'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:doc,link',
            'company_id' => 'required|integer',
            'upload' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'link' => 'url',
            'upload_or_link' => 'required_without_all:upload,link',
        ]);
        $data = [];

        try {
            $image = $request->file('upload');
            $data['name'] = $request->name;
            $data['type'] = $request->type;
            if ($image) {
                $filename = time() . '.' . $image->getClientOriginalName() . '_' . $image->extension();
                $image->move(public_path('/comp_docs'), $filename);
                $data['upload'] = $filename;
            } else {
                $data['upload'] = $request->link;
            }
            $data['company_id'] =  $request->company_id;
            $data['description'] = $request->description;
            $employees = [];
            if ($request->all_em == true) {
                $employees = Employee::where('company_id', $request->company_id)->pluck('id');
            } else {
                $employees = array_map('intval', explode(',', $request->employees_id));
            }

            foreach ($employees as $employee) {
                $data['employee_id'] = intval($employee);
                // return response()->json($data);
                CompanyDocsAndLinks::create($data);
            }
        } catch (\Exception $e) {
            return response()->json('File upload failed: ' . $e->getMessage(), 500);
        }
    }


    public function destroy($id)
    {
        $recordToDelete = CompanyDocsAndLinks::find($id);

        if ($recordToDelete) {
            $filePath = $recordToDelete->upload;
        
            $multipleDocs = CompanyDocsAndLinks::where('upload', $filePath)
                ->where('id', '!=', $id) 
                ->count();
        
            if ($multipleDocs < 1) {
                if (file_exists(public_path('comp_docs/' . $filePath))) {
                    unlink(public_path('comp_docs/' . $filePath));
                }
            }
        
            $recordToDelete->delete();
        
            return response()->json(['message' => 'Record deleted successfully']);
        } else {
            return response()->json(['message' => 'Record not found'], 404);
        }
        
    }

    public function multiple_delete(Request $request)
    {
        // return response()->json($request);
        $ids = $request->input('selectedIds');

        $recordsToDelete = CompanyDocsAndLinks::whereIn('id', $ids)->get();

        foreach ($recordsToDelete as $record) {
            $filePath = $record->upload;

            $multipleDocs = CompanyDocsAndLinks::where('upload', $filePath)
            ->where('id', '!=', $record->id) 
            ->count();
    
            if ($multipleDocs < 1) {
               if (file_exists(public_path('comp_docs/' . $filePath))) {
                unlink(public_path('comp_docs/' . $filePath));
               }
            }
        }

        CompanyDocsAndLinks::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'Records and associated files deleted successfully']);
    }
}
