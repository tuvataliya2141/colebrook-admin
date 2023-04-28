<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SizeCharts;
use App\Models\Category;


class SizeChartController extends Controller
{
    public function index(){
        $list = SizeCharts::all();
        // $size = [];
        // foreach ($list as $key => $value) {
        //     $size = json_decode($value->size_values);
        //     dd();
        //     // foreach ($size as $val) {
                
        //     // }
        //     $size = $value->image;                                       
        // }
        // dd(json_decode($size));
        return view('backend.size_chart.index', compact('list'));
    }

    public function add(){
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('backend.size_chart.create', compact('categories'));
    }

    public function store(Request $request){
        $data = [];
        $size = $request->size;
        $title = $request->title;
        $inches_value = $request->inches_value;
        $cm_value = $request->cm_value;

        for ($i = 0; $i < count($size); $i++) {
            if (!empty($size[$i]) && !empty($title[$i]) && !empty($inches_value[$i]) && !empty($cm_value[$i])) {
                $row = array(
                    "size" => $size[$i],
                    "title" => $title[$i],
                    "inches_value" => $inches_value[$i],
                    "cm_value" => $cm_value[$i]
                );
                $data[] = $row;
            }
        }

        $categories = Category::where('id', $request->category_id)->first();

        // dd(json_encode($data));
        $sizeChart = new SizeCharts;
        $sizeChart->name = $categories->name;
        $sizeChart->image = $request->image;
        $sizeChart->size_values = json_encode($data);
        $sizeChart->save();
        flash(translate('Size has been inserted successfully'))->success();
        return redirect()->route('size-chart.index');
    }

    public function edit($id){
        $sizeChart = SizeCharts::where('id', $id)->first();
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        // dd($sizeChart);
        return view('backend.size_chart.edit', compact('sizeChart', 'categories'));
    }

    public function update(Request $request){

        
        $data = [];
        $size = $request->size;
        $title = $request->title;
        $inches_value = $request->inches_value;
        $cm_value = $request->cm_value;

        for ($i = 0; $i < count($size); $i++) {
            if (!empty($size[$i]) && !empty($title[$i]) && !empty($inches_value[$i]) && !empty($cm_value[$i])) {
                $row = array(
                    "size" => $size[$i],
                    "title" => $title[$i],
                    "inches_value" => $inches_value[$i],
                    "cm_value" => $cm_value[$i]
                );
                $data[] = $row;
            }
        }
        $categories = Category::where('id', $request->category_id)->first();
        $sizeChart = SizeCharts::findOrFail($request->size_id);
        $sizeChart->name = $categories->name;
        $sizeChart->image = $request->image;
        $sizeChart->size_values = json_encode($data);
        $sizeChart->save();
        flash(translate('Size has been updated successfully'))->success();
        return redirect()->route('size-chart.index');
    }

    public function destroy($id){
        // dd('hii');
        $sizeChart = SizeCharts::where('id', $id)->delete();
        flash(translate('Size has been deleted successfully'))->success();
        return redirect()->route('size-chart.index');
    }
}
