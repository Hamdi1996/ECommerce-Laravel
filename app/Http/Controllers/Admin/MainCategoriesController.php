<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use Illuminate\Http\Request;
use App\Models\MainCategory;
use Exception;
use Illuminate\Support\Facades\DB;

class MainCategoriesController extends Controller
{
    //
    public function index()
    {
        $default_lang = get_default_lang();
        $categories = MainCategory::where('translation_lang', $default_lang)->select()->get();
        return view('admin.maincategories.index', compact('categories'));
    }
    public function create()
    {

        return view('admin.maincategories.create');
    }
    public function store(MainCategoryRequest $request)
    {
        try {
            // return category in collection to make filter
            $main_categories = collect($request->category);

            $filter = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] == get_default_lang();
            });
            $defalut_category =  array_values($filter->all());

            $filePath = "";
            if ($request->has('photo')) {
                $filePath = uploadImage('maincategories', $request->photo);
            }

            DB::beginTransaction();
            // Get Id of Default category to store in DB with id of translation
            $defalut_category_Id = MainCategory::insertGetId([
                'translation_lang' => $defalut_category[0]['abbr'],
                'translation_of' => 0,
                'name' => $defalut_category[0]['name'],
                'slug' => $defalut_category[0]['name'],
                'photo' => $filePath,

            ]);


            $categories = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] != get_default_lang();
            });


            if (isset($categories) && $categories->count()) {

                $categories_arr = [];
                foreach ($categories as $category) {
                    $categories_arr[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $defalut_category_Id,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'photo' => $filePath,

                    ];
                }

                MainCategory::insert($categories_arr);
            }
            DB::commit();
            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحفظ بنجاح']);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما يرجي المحاوله لاحقا']);
        }
    }

    public function edit($mainCat_id)
    {
        // Get Specific Category With other lang
        $mainCategory = MainCategory::with('categories')->selection()->find($mainCat_id);
        if (!$mainCategory) {
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجوده']);
        }
        return view('admin.maincategories.edit', compact('mainCategory'));
    }

    public function update($maincatId, MainCategoryRequest $request)
    {
        try {

            $main_category = MainCategory::find($maincatId);
            if (!$main_category) {
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);
            }

            //    Update Process

            $category = array_values($request->category)[0];
            if (!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
                else 
                $request->request->add(['active' => 1]);


                MainCategory::where('id', $maincatId)
                ->update([
                    'name' => $category['name'],
                    'active' => $request->active,
                ]);

            // if request have photo update photo 

            if ($request->has('photo')) {
                $filePath = uploadImage('maincategories', $request->photo);
                MainCategory::where('id', $maincatId)
                    ->update([
                        'photo' => $filePath,
                    ]);
            }

            return redirect()->route('admin.maincategories')->with(['success' => 'تم التحديث بنجاح']);
        } catch (Exception $ex) {

           
            return redirect()->route('admin.maincategories')->with(['error' => ' هناك خطا ما يرجي المحاوله لاحقا']);
        }
    }

    public function destroy($id)
    {
        try{
            $category = MainCategory::find($id);
            if(!$category){
                return redirect()->route('admin.maincategories.edit',$id)->with(['error'=>'هذا القسم غير موجوده']);
            }
            $vendors = $category->vendors();
            if(isset($vendors) && $vendors->count()>0){
                return redirect()->route('admin.maincategories')->with(['error'=>'لايمكن حذف هذا القسم   ']);
            }
            $category->delete();
            return redirect()->route('admin.maincategories')->with(['success'=>'تم حذف القسم بنجاح  ']);

         }
         catch(Exception $ex){

             return redirect()->route('admin.maincategories')->with(['error'=>' هناك خطا ما يرجي المحاوله لاحقا']);

         }
    }
}
