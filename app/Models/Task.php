<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory; // ← ajouter ça

    protected $fillable = ['title', 'status', 'due', 'project_id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
