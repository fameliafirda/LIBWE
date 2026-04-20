<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class SidebarComposer
{
    public function compose(View $view)
    {
        $categories = Category::all();
        $view->with('sidebarCategories', $categories);
    }
}