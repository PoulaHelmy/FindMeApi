<?php

namespace App\Http\Resources\Items;

use App\Models\Category;
use App\Models\ItemOption;
use App\Models\Photo;
use App\Models\Subcat;
use App\Models\Question;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class ItemsDetailsResource extends JsonResource
{

    public function toArray($request)
    {

        $Alloptions=[];
        $AllPhotos=[];
        $AllQuestions=[];
        foreach($this->dynamicValues as $option)
        {
            $row = ItemOption::find($option->id);
            array_push($Alloptions,[$option]);
        }
        foreach($this->questions as $question)
        {
            $row = Question::find($question->id);
            array_push($AllQuestions,[$question]);
        }
        foreach($this->photos as $photo)
        {
            $photodddd = Photo::find($photo->id);
            $extensions = pathinfo($photodddd->src, PATHINFO_EXTENSION);//ext is "html"
            $image ='data:image/' . $extensions  . ';base64,'.base64_encode(Storage::disk('public')->get($photodddd->src));
            $photodddd['src']=$image;
            array_push($AllPhotos,[$photodddd]);
        }
        return [
            'id'            =>$this->id,
            'userId' => $this->user_id,
            'name'          =>$this->name,
            'category_id'   =>$this->category_id,
            'category'   =>Category::find($this->category_id)->name,
            'subcat_id'  =>$this->subcat_id,
            'subcat'  =>Subcat::find($this->subcat_id)->name,
            'location'   =>$this->location,
            'lat'       =>$this->lat,
            'lan' =>$this->lan,
            'AllQuestions'   =>$AllQuestions,
            'description'  =>$this->des,
            'is_found'   =>$this->is_found,
            'date'  =>$this->date,
            'images'=>$AllPhotos,
            'dynamicValues'=>$Alloptions,
            'created_at'    =>$this->created_at,
            'optionsCount'=>sizeof($Alloptions),
            'imagesCount'=>sizeof($AllPhotos),
            'questionsCount'=>sizeof($AllQuestions),
        ];
    }
}
