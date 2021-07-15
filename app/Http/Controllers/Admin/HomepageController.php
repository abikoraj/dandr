<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aboutus;
use App\Models\Gallery;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function abountus(Request $request){
        if($request->isMethod('post')){
            // dd($request->all());

            $about = Aboutus::where('id',$request->id)->first();
            $about->title1 = $request->title1;
            $about->title2 = $request->title2;
            $about->title3 = $request->title3;

            $about->desc1 = $request->desc1;
            $about->desc2 = $request->desc2;
            $about->desc3 = $request->desc3;
            // dd($about);
            $about->save();
            return redirect()->back()->with('message','Updated successfully !');
        }else{
            $about = Aboutus::first();
            return view('admin.homepage.about',compact('about'));
        }
    }

    public function sliders(Request $request){
        if($request->isMethod('post')){
            // dd($request->all());
            $slider = new Slider();
            $slider->heading = $request->heading;
            $slider->title = $request->title;
            $slider->image=$request->image->store('image');
            $slider->save();
            return redirect()->back()->with('message','Slider Added successfully !');
        }else{
            $slider = Slider::all();
            return view('admin.homepage.slider.index',compact('slider'));
        }
    }

    public function sliderDel($id){
        $d = Slider::find($id);
        $d->delete();
        return redirect()->back()->with('message','Deleted successfully !');
    }

    public function gallery(Request $request){
        if($request->isMethod('post')){

            // dd($request->images);
            // dd($request->all());
            foreach ($request->images as  $image) {
                $gallery=new Gallery();
                $gallery->image=$image->store('galiamge');
                $gallery->caption=$request->caption??"";
                $gallery->save();
            }
            return redirect()->back()->with('message','Slider Added successfully !');
        }else{

            return view('admin.homepage.gallery.index');
        }
    }

    public function galleryDel(Gallery $gallery){
        $id=$gallery->id;
        $gallery->delete();
        return response()->json(['id'=>$id]);
    }
}
