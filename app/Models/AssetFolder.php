<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetFolder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'user_id',
    ];

    // Relasi ke Sub-folder (Children)
    public function children()
    {
        return $this->hasMany(AssetFolder::class, 'parent_id');
    }

    // Relasi ke Parent Folder
    public function parent()
    {
        return $this->belongsTo(AssetFolder::class, 'parent_id');
    }

    // Relasi ke Files (Assets) di dalam folder ini
    public function assets()
    {
        return $this->hasMany(Asset::class, 'folder_id');
    }

    // Relasi ke Creator
    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Mendapatkan Breadcrumb Path (Recursive)
    public function getPathAttribute()
    {
        $path = collect([$this]);
        $parent = $this->parent;
        
        while($parent) {
            $path->prepend($parent);
            $parent = $parent->parent;
        }

        return $path;
    }
}
