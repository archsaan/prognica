<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use PDF;

class DashboardController extends Controller {

    /**
     * Show a form to evaluate Ultrasound scanned images
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('pages.dashboard');
    }
    /**
     * Show the form to choose the type of upload
     * 
     * @created by Jayalakshmi Ramasamy on 27/01/2019
     */
     
    public function chooseOption(){
        return view('pages.options');
    }
    
    /**
     * Upload UltraSound image into filesystem
     * 
     * @required data: File[]
     * @optional data: none
     * 
     * @return JSON uploaded image path and image name
     * 
     */
    public function UploadImage(Request $request) {
        $files = $request->all();
        $response = ['status' => 0, 'Response' => 'Couldn\'t Upload the image. Please try again'];
        if ($request->hasFile(0)) {
            $destinationPath = 'public/uploads/ultraImages/series/001/orig';
            foreach ($files as $file) {
                $filename = time() . '.' . $file->getClientOriginalExtension();
                if ($file->move($destinationPath, $filename)) {
                   $response = ['status' => 1, 'data' => ['filename' => $filename, 'path' => './uploads/ultraImages']]; 
                }
            }
        }
        
        return response()->json($response);
    }
    
    /*
     * Download sample image
     * 
     * @required data: none
     * @optional data: none
     * 
     * @return image
     */
    public function DownloadSample(){
        return response()->download(public_path('images/sample.jpg'));
    }

    public function pdf(){
        $data['test']="ddd";
          $pdf = PDF::loadView('pdf', $data);
         return $pdf->download('invoice.pdf');
            }

    

}
