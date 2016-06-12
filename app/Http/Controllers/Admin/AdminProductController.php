<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Settings;
use App\StaticData\Currency;
use Illuminate\Http\Request;

class AdminProductController extends AdminController
{
    /**
     * Renders add form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAdd()
    {
        $locales = Settings::getLocales();
        $data = [
            'locales' => $locales,
            'currencies' => Currency::$all,
        ];

        return view('admin.product.add', $data);
    }

    /**
     * Handles product add
     *
     * @param ProductRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAdd(ProductRequest $request)
    { 
        $data = $request->input();
        $data['files'] = $request->files->all();
        $resp = Product::createRecord($data);

        if ($resp === true) {
            flash()->success(trans('product.add_success'));

            return redirect()->back();
        }

        flash()->error($resp);

        return redirect()->back();
    }

    /**
     * Renders edit page
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit($id)
    {
        $product = Product::with(['productImages', 'productTranslations'])
            ->where(['id' => $id])
            ->get()
            ->first();
        $locales = Settings::getLocales();

        $data = [
            'mainImage' => ProductImage::findMainImage($product->id),
            'product' => $product,
            'locales' => $locales,
            'currencies' => Currency::$all,
            'deleteMessage' => trans('product.image_remove_confirmation'),
        ];

        return view('admin.product.edit', $data);
    }

    /**
     * Handles product edit
     *
     * @param integer $id
     * @param ProductRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putEdit($id, ProductRequest $request)
    {
        $data = $request->input();
        $data['files'] = $request->files->all();
        $resp = Product::updateRecord($id ,$data);

        if ($resp === true) {
            flash()->success(trans('product.edit_success'));

            return redirect()->back();
        }

        flash()->error($resp);

        return redirect()->back();
    }

    /**
     * Renders all page
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function getAll(Request $request)
    {
        $query = Product::getAll(true, true);

        return $this->all(
            $request,
            $query,
            'admin/products',
            trans('product.all_title'),
            trans('product.delete_message'),
            'admin.product.all'
        );
    }

    /**
     * Handles delete
     *
     * @param integer $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete($id)
    {
        $product = Product::find($id);
        $product->delete();
        flash()->success(trans('product.remove_success'));

        return redirect()->back();
    }

    /**
     * Gets search page
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function getSearch(Request $request)
    {
        $order = $request->input('order');
        $param = $request->input('param');
        $search = $request->input('search');

        try {
            $results = Product::search(
                $this->search(
                    $search,
                    [
                        'proximity' => false,
                        'phrase' => false,
                    ]
                ),
                $order,
                $param
            );
;
            $count = count($results);

            $data = [
                'results' => $results,
                'param' => $param,
                'order' => $order,
                'search' => $search,
                'uri' => 'admin/products',
                'count' => $count,
                'all' => Product::count(),
                'delete_message' => trans('product.delete_message'),
            ];
        } catch (\Exception $e) {
            $data = [
                'results' => [],
                'param' => null,
                'order' => null,
                'search' => $search,
                'uri' => 'admin/products',
                'count' => 0,
                'all' => 0,
                'delete_message' => trans('product.delete_message'),
            ];
        }

        return view('admin.product.search', $data);
    }

    /**
     * Handles search
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function postSearch(Request $request)
    {
        return $this->getSearch(
            $request,
            $request->input('search'),
            null,
            null
        );
    }
}
