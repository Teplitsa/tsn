<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;

class Controller extends BaseController
{
    /** @var Collection */
    private $actionButtons = null;
    /** @var Collection */
    private $toastrs = null;
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function addActionButton($text, $href, $class = 'btn btn-primary', $icon = '')
    {
        if ($this->actionButtons === null) {
            $this->actionButtons = collect([]);
        }

        $this->actionButtons->push(compact('text', 'href', 'class', 'icon'));
    }

    protected function addToastr($type, $message, $title = null, $onlick = null)
    {
        if ($this->toastrs === null) {
            $this->toastrs = collect([]);
        }

        $this->toastrs->push(toastr($type, $message, $title, $onlick));
    }

    public function callAction($method, $parameters)
    {
        $result = parent::callAction($method, $parameters);
        if ($this->actionButtons !== null) {
            view()->share('actionButtons', $this->actionButtons);
        } else {
            view()->share('actionButtons', '[]');
        }

        $breadcrumbs = \Breadcrumbs::generateIfExistsArray(\Route::getCurrentRoute()->getName(), $parameters) ?: [];
        view()->share('breadcrumbs', json_encode($breadcrumbs));

        $toastrs = $this->toastrs ? $this->toastrs->toJson() : '[]';
        view()->share('toastrs', $toastrs);

        if (request()->wantsJson()) {
            if ($result instanceof View) {
                $result = [
                    'data' => [
                        'content' => $result->render(),
                    ],
                ];
            }

            if (!is_array($result) || !array_key_exists('data', $result)) {
                $result = [
                    'data' => $result,
                ];
            }

            if (!array_key_exists('meta', $result)) {
                $result['meta'] = [];
            }

            if (!empty($breadcrumbs)) {
                $result['meta']['breadcrumbs'] = $breadcrumbs;
            }

            if (null !== $this->toastrs) {
                $result['meta']['toastrs'] = $this->toastrs->toArray();
            }
        }

        return $result;
    }
}
