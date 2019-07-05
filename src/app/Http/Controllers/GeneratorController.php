<?php

namespace AlexClaimer\Generator\App\Http\Controllers;


use AlexClaimer\Generator\App\Services\Generator\Main;
use AlexClaimer\Generator\App\Services\Generator\MakeSeeds;
use AlexClaimer\Generator\App\Services\Generators\Packages\MakePackages;
use AlexClaimer\Generator\App\Services\Generators\Migrations\MakeMigration;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class GeneratorController extends BaseController
{
    public function __construct()
    {
        $this->middleware('web');//11 uncomment
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
               // '_token' => $all['_token'],
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function menu_create_migration()
    {
        return view('generator_views::migration/menu_migration_generator');
    }

    /**
     * @throws \Throwable
     */
    public function store_migration(Request $request)
    {
        $param = $request->all();

        $MakeMigration = new MakeMigration($param);
        $result = $MakeMigration->GenerateMigration($param, $message);

        if ($result) {
            return redirect('generator_create_migration')
                ->with([
                    'messages' => 'Migrations created successfully'
                ]);
        } else {
            return redirect('generator_create_migration')
                ->withErrors(['msg' => ['Migrations created error', $message]])
                ->withInput();
        }


    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function menu_create_seeders()
    {
        return view('generator_views::seeders/menu_seeders_generator');
    }

    /**
     * @throws \Throwable
     */
    public function store_seeders(Request $request)
    {
        $param = $request->all();

        $MakeSeeders= new MakeSeeds();
        $result = $MakeSeeders->GenerateSeeders($message);

        if ($result) {
            return redirect('generator_create_seeders')
                ->with([
                    'messages' => 'Seeders created successfully'
                ]);
        } else {
            return redirect('generator_create_seeders')
                ->withErrors(['msg' => ['Seeders created error', $message]])
                ->withInput();
        }


    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function menu_create_packages()
    {
        return view('generator_views::package/menu_package_generator');
    }

    /**
     * @throws \Throwable
     */
    public function store_packages(Request $request)
    {
        $param = $request->all();

        //dd(__METHOD__, $param);
        $VendorName =  ucfirst(Str::camel($param['vendor-name']));
        $PackageName =  ucfirst(Str::camel($param['package-name']));

        $MakePackages = new MakePackages($param['vendor-name'], $param['package-name']);
        $result = $MakePackages->GeneratePackages($message);
        $message_add = "    \"autoload-dev\": {
        \"psr-4\": {

            ...
            \"$VendorName\\\\$PackageName\\\\\": \"packages/$VendorName/$PackageName/src\",
            ...
        }
    },";
        $message_add2 = "$VendorName\\$PackageName\\App\\Providers\\" . $PackageName . "ServiceProvider::class,";
        if ($result) {
            return redirect('generator_create_packages')
                ->with([
                    'messages' => [
                        'Package created successfully',
                        $message_add,
                        $message_add2
                        ]
                ]);
        } else {
            return redirect('generator_create_packages')
                ->withErrors(['msg' => ['Package created error', $message]])
                ->withInput();
        }


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
