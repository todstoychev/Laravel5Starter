<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductImage;
use Todstoychev\Icr\Icr;

/**
 * Class AdminProductImageController
 * 
 * @package App\Http\Controllers\Admin
 */
class AdminProductImageController extends AdminController
{
    /**
     * Removes image
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete($id)
    {
        $image = ProductImage::findOrFail($id);
        Icr::deleteImage($image->image_name, 'product', 'images');
        $image->delete($image->id);
        flash()->success(trans('product.image_remove_success'));

        return redirect()->back();
    }
}
