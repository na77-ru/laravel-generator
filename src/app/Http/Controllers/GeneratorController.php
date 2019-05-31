<?php

namespace AlexClaimer\Generator\App\Http\Controllers;


use AlexClaimer\Generator\App\Services\Generator\Main;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class GeneratorController extends BaseController
{
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generator_menu()
    {
        return view('generator_views::menu');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_alex_claimer_generator_config()
    {
        return view('generator_views::show_alex_claimer_generator_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generate_patch(Request $request)
    {
        $all = $request->all();
        //dd(__METHOD__, $request, $all);
        new Main();

        return redirect()
            ->route('generator_menu')
            ->with([
                '_token' => $all['_token'],
                'messages' => 'All classes generated successfully.',
                'alert-type' => 'success',
            ]);
        //dd(__METHOD__, $model);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generate_get()
    {
        new Main();

        return redirect()
            ->route('generator_menu')
            ->with([
                'messages' => 'All classes generated successfully.',
                'alert-type' => 'success',
            ]);
        //dd(__METHOD__, $model);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd(__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd(__METHOD__, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request = new Request();
        dd(__METHOD__, $request, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $request = new Request();
        dd(__METHOD__, $request, $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        dd(__METHOD__, $request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $request = new Request();
        dd(__METHOD__, $request, $id);
    }
}
