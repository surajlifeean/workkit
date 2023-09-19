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
}
