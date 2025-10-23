<?php

namespace App\Observers;

use App\Models\Menu;
use App\Models\MenuCategory;

class MenuCategoryObserver
{
    /**
     * Handle the MenuCategory "created" event.
     */
    public function created(MenuCategory $menuCategory): void
    {
        //
    }

    /**
     * Handle the MenuCategory "updated" event.
     */
    public function updated(MenuCategory $menuCategory): void
    {
        //
    }

    /**
     * Handle the MenuCategory "deleted" event.
     */
    public function deleting(MenuCategory $menuCategory): void
    {
        $this->reassignMenusToDefault($menuCategory);
    }

    /**
     * Handle the MenuCategory "restored" event.
     */
    public function restored(MenuCategory $menuCategory): void
    {
        $this->reassignMenusToDefault($menuCategory);
    }

    private function reassignMenusToDefault(MenuCategory $menuCategory): void
    {
        $defaultCategory = MenuCategory::firstOrCreate(['name' => 'Belum ada Kategori']);

        Menu::where('menu_category_id', $menuCategory->id)
            ->update(['menu_category_id' => $defaultCategory->id]);
    }
    /**
     * Handle the MenuCategory "force deleted" event.
     */
    public function forceDeleted(MenuCategory $menuCategory): void
    {
        //
    }
}
