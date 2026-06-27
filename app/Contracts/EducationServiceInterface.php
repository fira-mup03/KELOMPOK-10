<?php

namespace App\Contracts;

interface EducationServiceInterface
{
    // Menampilkan seluruh artikel edukasi kesehatan gigi
    public function getEducationArticles();

    // Menampilkan detail artikel edukasi berdasarkan ID
    public function getEducationArticleById(int $id);
}
