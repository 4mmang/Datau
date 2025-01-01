<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatasetFeatureType extends Model
{
    use HasFactory;
    protected $table = 'dataset_feature_types';
    protected $guarded = [];

    public function feature(){
        return $this->belongsTo(FeatureType::class, 'id_feature_type');
    }
}
