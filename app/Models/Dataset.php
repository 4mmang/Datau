<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasFactory;
    protected $table = 'datasets';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function subjectArea()
    {
        return $this->belongsTo(SubjectArea::class, 'id_subject_area');
    }

    public function characteristics(){
        return $this->hasMany(DatasetCharacteristic::class, 'id_dataset');
    }

    public function associatedTask(){
        return $this->hasMany(DatasetAssociatedTask::class, 'id_dataset');
    }
    
    public function featuresType()
    {
        return $this->hasMany(DatasetFeatureType::class, 'id_dataset');
    }
}
