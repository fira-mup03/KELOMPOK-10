<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'category', 'thumbnail', 'content', 'read_time', 'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== 'semua') {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'perawatan' => 'Perawatan',
            'penyakit'  => 'Penyakit',
            'tips'      => 'Tips',
            'nutrisi'   => 'Nutrisi',
            default     => ucfirst($this->category),
        };
    }
}
