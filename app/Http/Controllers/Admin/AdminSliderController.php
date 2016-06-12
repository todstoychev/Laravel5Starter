<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SliderRequest;
use App\Models;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Todstoychev\Icr\Icr;

/**
 * Class AdminSliderController
 * 
 * @package App\Http\Controllers\Admin
 */
class AdminSliderController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAdd()
    {
        $data = [
            'locales' => Models\Settings::getLocales(),
        ];

        return view('admin.slider.add', $data);
    }

    /**
     * Handles slider add
     *
     * @param SliderRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAdd(SliderRequest $request)
    {
        $data = $request->input();
        $data['image'] = $request->file('image');
        $resp = Models\Slider::createRecord($data);

        if (true === $resp) {
            flash()->success(trans('slider.add_success'));

            return redirect()->back();
        }

        flash()->error($resp);

        return redirect()->back();
    }

    /**
     * Gets the edit page
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit($id)
    {
        $slider = Models\Slider::with(['translations'])
            ->where(['id' => $id])
            ->get()
            ->first();

        $data = [
            'locales' => Models\Settings::getLocales(),
            'slider' => $slider,
        ];

        return view('admin.slider.edit', $data);
    }

    /**
     * Handles item edit
     *
     * @param integer $id
     * @param SliderRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putEdit($id, SliderRequest $request)
    {
        $resp = Models\Slider::updateRecord($id, $request->input());

        if (true === $resp) {
            flash()->success(trans('slider.edit_success'));

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
        $query = Models\Slider::getAll();

        return $this->all(
            $request,
            $query,
            'admin/slider',
            trans('slider.all_title'),
            trans('slider.delete_message'),
            'admin.slider.all'
        );
    }

    /**
     * Handles remove action
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete($id)
    {
        $slider = Models\Slider::find($id);
        Icr::deleteImage($slider->image_name, 'slider', 'images');
        $slider->delete();
        flash()->success(trans('slider.remove_success'));

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
            $results = Models\Slider::search(
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

            $count = count($results);

            $data = [
                'results' => $results,
                'param' => $param,
                'order' => $order,
                'search' => $search,
                'uri' => 'admin/slider',
                'count' => $count,
                'all' => Models\Slider::count(), // Use cached query
                'delete_message' => trans('slider.delete_message'),
                'carbon' => new Carbon(),
            ];
        } catch (\Exception $e) {
            $data = [
                'results' => [],
                'param' => null,
                'order' => null,
                'search' => $search,
                'uri' => 'admin/slider',
                'count' => 0,
                'all' => 0,
                'delete_message' => trans('slider.delete_message'),
                'carbon' => new Carbon(),
            ];
        }

        return view('admin.slider.search', $data);
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
