<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Css extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    protected $path;
    protected $cache;
    public function __construct($path='')
    {
        //
        $this->path=$path;
        $this->cache=env('BROWSER_CACHE');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */

    public function render()
    {
        return view('components.css',['path'=>$this->path,'cache' =>$this->cache]);
    }
}
