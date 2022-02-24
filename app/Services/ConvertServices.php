<?php

use App\Models\Like;

class ConvertServices
{
    static public function convertLikesModelsToNames($like)
    {
        $names[] = [];
        for ($i = 0; $i < sizeof($like); $i++) {
            $names[] = $like->User()->name;
        }
        return $names;
    }
}
