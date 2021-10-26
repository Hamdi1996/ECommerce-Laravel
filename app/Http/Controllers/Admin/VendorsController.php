<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use App\Notifications\VendorCreated;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class VendorsController extends Controller
{
   public function index()
   {
      $vendors = Vendor::selection()->paginate(PAGINATION_COUNT);
      return view('admin.vendors.index',compact('vendors'));
       
   }
   public function create()
   {
       $categories = MainCategory::where('translation_lang',0)->active()->get();
       return view('admin.vendors.create',compact('categories'));
   }
   public function store(VendorRequest $request)
   {
       try{
        if (!$request->has('active'))
        $request->request->add(['active' => 0]);
        else 
        $request->request->add(['active' => 1]);
        $filePath='';
        if ($request->has('logo')) {
            $filePath = uploadImage('vendors', $request->logo);
            
        }
        $vendor = Vendor::create([

            'name' =>$request->name,
            'mobile'=>$request->mobile,
            'email'=>$request->email,
            'active'=>$request->active,
            'logo'=>$filePath,
            'category_id'=>$request->category_id,
            'password'=>$request->password,

        ]);

        Notification::send($vendor,new VendorCreated($vendor));
        return redirect()->route('admin.vendors')->with(['success' => 'تم الحفظ بنجاح']);


       }
       catch(Exception $ex){
        //    return $ex;
        return redirect()->route('admin.vendors')->with(['error'=>' هناك خطا ما يرجي المحاوله لاحقا']);

       }
   }
   public function edit($id)
   {
       try{
           $vendor = Vendor::Selection()->find($id);
           if(!$vendor)
        return redirect()->route('admin.vendors')->with(['error'=>' هذا المتجرغير موجود او ربما يكون محذوف']);
       $categories = MainCategory::where('translation_lang',0)->active()->get();

       return view('admin.vendors.edit',compact('vendor','categories'));

       }
       catch(Exception $ex){
        return redirect()->route('admin.vendors')->with(['error'=>' هناك خطا ما يرجي المحاوله لاحقا']);

       }
       
   }
   public function update($id , VendorRequest $request)
   {
       try{
    $vendor = Vendor::Selection()->find($id);
       if(!$vendor)
       return redirect()->route('admin.vendors')->with(['error'=>' هذا المتجرغير موجود او ربما يكون محذوف']);

       DB::beginTransaction();
       if ($request->has('logo')) {
        $filePath = uploadImage('vendors', $request->logo);
        Vendor::where('id', $id)
            ->update([
                'logo' => $filePath,
            ]);
    }
    $data =$request->except('_token','id','logo','password');
       if ($request->has('password')) {
      $data['password']= $request->password;
    }

    Vendor::where('id',$id)->update($data);
    DB::commit();
    return redirect()->route('admin.vendors')->with(['success' => 'تم التحديث بنجاح']);
        


    }
    catch(Exception $ex){
        DB::rollBack();
        return $ex;
        return redirect()->route('admin.vendors')->with(['error'=>' هناك خطا ما يرجي المحاوله لاحقا']);

       }
   }
   public function changeStatus()
   {
       
   }
}
