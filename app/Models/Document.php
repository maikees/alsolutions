<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['title', 'content', 'category_id'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($document) {
            $category = $document->category->name;

            if ($category === 'Remessa' && !str_contains($document->title, 'semestre')) {
                throw new \Exception('O título deve conter a palavra "semestre" para a categoria "Remessa".');
            }

            if ($category === 'Remessa Parcial' && !self::containsMonth($document->title)) {
                throw new \Exception('O título deve conter o nome de um mês para a categoria "Remessa Parcial".');
            }
        });
    }

    private static function containsMonth($title)
    {
        $months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

        foreach ($months as $month) {
            if (str_contains($title, $month)) {
                return true;
            }
        }

        return false;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
