<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
  public function faqsManage()
  {
      $faqs = Faq::latest()->paginate(10);
      return view('admin.faqs', compact('faqs'));
  }

  public function faqsStore(Request $request)
  {
    try {
      $request->validate([
        'question' => 'required|string|unique:faqs,question',
        'answer' => 'required|string',
      ]);

      $faqs = Faq::create([
        'question' => $request->question,
        'answer' => $request->answer,
        'status' => true,
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Faqs added successfully.',
        'data' => $faqs
      ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('StoreFaqs error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to add Faqs. Please try again.'
      ], 500);
    }
  }

 public function faqsList(Request $request)
  {
      if ($request->ajax()) {
          // include created_at in select
          $data = Faq::select('id', 'question', 'answer', 'created_at')
                     ->orderBy('created_at', 'desc'); 

          return DataTables::of($data)
              ->addIndexColumn()
              ->editColumn('created_at', function ($row) {
                  return \Carbon\Carbon::parse($row->created_at)->format('d M Y');
              })
              ->addColumn('action', function ($row) {
                  $btn = '<button class="btn btn-sm btn-warning viwBtn mx-1" data-id="' . $row->id . '">View</button>';
                  $btn .= '<button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button>';
                  $btn .= ' <button class="btn btn-sm btn-danger delBtn delfaqsBtn" data-id="' . $row->id . '">Delete</button>';
                  return $btn;
              })
              ->rawColumns(['action']) 
              ->make(true);
      }

      return response()->json(['error' => 'Unauthorized'], 401);
  }


  public function faqsEdit($id)
  {
    try {
      $faqs = Faq::findOrFail($id);
      return response()->json($faqs);
    } catch (\Exception $e) {
      \Log::error('Editfaqs error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to load faqs data.'
      ], 500);
    }
  }

  public function faqsUpdate(Request $request, $id)
  {

    try {
      $request->validate([
        'question' => 'required|string||unique:faqs,question,' . $id,
        'answer' => 'required|string',
      ]);

      $faqs = Faq::findOrFail($id);
      $faqs->update([
        'question' => $request->question,
        'answer' => $request->answer,
      ]);

      return response()->json([
        'success' => true,
        'message' => 'faqs updated successfully',
        'data' => $faqs
      ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('Updatefaqs error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to update faqs. Please try again.'
      ], 500);
    }

  }

  public function faqsDestroy($id)
  {
    try {
      $faqs = Faq::findOrFail($id);
      $faqs->delete();

      return response()->json([
        'success' => true,
        'message' => 'faqs deleted successfully.'
      ], 200);
    } catch (\Exception $e) {
      \Log::error('Deletefaqs error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete faqs. Please try again.'
      ], 500);
    }
  }


  public function viewFaqs($id)
  {
      $faq = Faq::findOrFail($id);

      return response()->json([
          'id'        => $faq->id,
          'question'  => $faq->question,
          'answer'    => $faq->answer,
          'created_at'=> $faq->created_at->format('d M Y'),
      ]);
  }

  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
}
