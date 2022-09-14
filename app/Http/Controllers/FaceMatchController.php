<?php

namespace App\Http\Controllers;

use App\Traits\FaceMatchTrait;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class FaceMatchController extends Controller
{
    use facematchtrait;
    //    Call this method first
    public function createFaceListM($id)
    {
       return $this->createFaceList($id);
    }

    // Call this method second and get the perssistedFaceId , pass an array of images
    public function addFacetoListm($facelistID, array $urls = ['https://i.ibb.co/jRc1w7y/harishan-kobalasingam-Uflm-CP-Nk-Ig-unsplash.jpg'])
    {
      return  $this->addFacetoList($facelistID,$urls);
    }

    // call this function last
    public function faceDetectandMatchM($imageUrl, Request $request)
    {
        return $this->faceDetectandMatch($imageUrl,$request);

    }


}