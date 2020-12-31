<?php

namespace App\Http\Controllers;
use App\SmGeneralSettings;
use App\SmNews;
use App\ApiBaseMethod;
use App\SmNewsCategory;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;


class SmNewsController extends Controller
{

    public function __construct()
	{
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function index()
    {

        try{
            $news = SmNews::get();
            $news_category = SmNewsCategory::get();
            return view('backEnd.news.news_page', compact('news', 'news_category'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'date' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png',
            'description' => 'required',
        ]);

        try{
            $news = new SmNews();
            $image = "";
            $date = strtotime($request->date);
            $newformat = date('Y-m-d', $date);
            if ($request->file('image')) {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('image');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('image');
                $image = 'stu-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/news/', $image);
                $image = 'public/uploads/news/' . $image;
            }
            $news->news_title = $request->title;
            $news->category_id = $request->category_id;
            $news->publish_date = $newformat;
            $news->image = $image;
            $news->news_body = $request->description;
            $result = $news->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }


    public function edit($id)
    {

        try{
            $news = SmNews::get();
            $add_news = SmNews::find($id);
            $news_category = SmNewsCategory::get();
            return view('backEnd.news.news_page', compact('add_news', 'news', 'news_category'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'date' => 'required',
            'description' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png',
        ]);

        try{
            $news = SmNews::find($request->id);
            $date = strtotime($request->date);
            $newformat = date('Y-m-d', $date);

            $image = "";
            if ($request->file('image') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('image');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $news = SmNews::find($request->id);
                if ($news->image != "") {
                    unlink($news->image);
                }


                $file = $request->file('image');
                $image = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/news/', $image);
                $image = 'public/uploads/news/' . $image;
            }

            $news = SmNews::find($request->id);
            $news->news_title = $request->title;
            $news->category_id = $request->category_id;
            $news->publish_date = $newformat;
            if ($image != "") {
                $news->image = $image;
            }
            $news->news_body = $request->description;
            $result = $news->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect('news');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }


    public function newsDetails($id)
    {

        try{
            $news = SmNews::find($id);
            return view('backEnd.news.news_details', compact('news'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }
    public function forDeleteNews($id)
    {

        try{
            return view('backEnd.news.delete_modal', compact('id'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function delete($id)
    {

        try{
            $news = SmNews::find($id);
            $result = $news->delete();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function newsCategory()
    {

        try{
            $newsCategories = SmNewsCategory::get();
            return view('backEnd.news.news_category', compact('newsCategories'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
        ]);

        try{
            $news_category = new SmNewsCategory();
            $news_category->category_name = $request->category_name;
            $result = $news_category->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function editCategory($id)
    {
        try{
            $newsCategories = SmNewsCategory::get();
            $editData = SmNewsCategory::find($id);
            return view('backEnd.news.news_category', compact('newsCategories', 'editData'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function updateCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
        ]);

        try{
            $news_category = SmNewsCategory::find($request->id);
            $news_category->category_name = $request->category_name;
            $result = $news_category->save();
            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect('news-category');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function forDeleteNewsCategory($id)
    {

        try{
            return view('backEnd.news.category_delete_modal', compact('id'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    public function deleteCategory(Request $request, $id)
    {
        try{
        $fk_id = 'category_id';

        $tables = \App\tableList::getTableList($fk_id,$id);

        try {
            $delete_query = SmNewsCategory::destroy($id);
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                if ($delete_query) {
                    return ApiBaseMethod::sendResponse(null, 'News Category has been deleted successfully');
                } else {
                    return ApiBaseMethod::sendError('Something went wrong, please try again.');
                }
            } else {
                if ($delete_query) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
            Toastr::error('This item already used', 'Failed');
            return redirect()->back();
           }
        } catch (\Exception $e) {
            //dd($e->getMessage(), $e->errorInfo);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}