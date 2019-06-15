<?php

namespace AlexClaimer\Generator\App\Services\Generators\Packages;

use AlexClaimer\Generator\App\Services\Generator\Helper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


class MakePackages
{

    protected $packageVendor;
    protected $packageName;

    public function __construct($packageVendor, $packageName)
    {
        $this->packageVendor = $packageVendor;
        $this->packageName = $packageName;
    }

    public function GeneratePackages(&$message = null)
    {
        $PacVen = ucfirst(Str::camel($this->packageVendor));
        $PacName = ucfirst(Str::camel($this->packageName));

        $this->generatePackageCommand($PacVen, $PacName);
        $this->generatePackageController($PacVen, $PacName);
        $this->generatePackageMiddleware($PacVen, $PacName);
        $this->generatePackageServiceProvider($PacVen, $PacName);
        $this->generatePackageKernel($PacVen, $PacName);
        $this->generatePackageRoutesWeb($PacVen, $PacName);
        $this->generatePackageAppBlade($PacVen, $PacName);
        $this->generatePackageViewMenu($PacVen, $PacName);
        $this->generatePackageViewIncMessages($PacVen, $PacName);
        $this->generatePackageViewIncErrors($PacVen, $PacName);
        $this->generatePackageViewCss($PacVen, $PacName);
        $this->generatePackageViewJs($PacVen, $PacName);
        $this->generatePackageComposerJson($PacVen, $PacName);



        $this->generatePackageViewIncMenuForm($PacVen, $PacName);

        return true;
    }

    protected function generatePackageComposerJson($PacVen, $PacName)
    {
        $ClassName = 'composer.json';
        $output = file_get_contents(__DIR__ . "/Stubs/ComposerJson.stub");
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "app" );
        $path_name = str_replace('\app.php', 'composer.json', $path_name);

        $output = $this->str_replace($output, $PacVen, $PacName, $ClassName);

        file_put_contents($path_name, $output);

        return true;
    }

    protected function generatePackageViewJs($PacVen, $PacName)
    {
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "resources/js/app");

        $path_name = str_replace('\app.php', 'app.js', $path_name);

        $success = \File::copy(__DIR__ . ('/../../../../../resources/js/app.js'), $path_name);

        return $success;
    }


    protected function generatePackageViewCss($PacVen, $PacName)
    {
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "resources/css/app");

        $path_name = str_replace('\app.php', 'app.css', $path_name);

        $success = \File::copy(__DIR__ . ('/../../../../../resources/css/app.css'), $path_name);

        return $success;
    }


    protected function generatePackageViewIncErrors($PacVen, $PacName)
    {
        $ClassName = "errors.blade";

        $output = file_get_contents(__DIR__ . "/Stubs/GenerateViewsIncErrors.stub");
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "resources/views/inc/" . $ClassName);

        $output = $this->str_replace($output, $PacVen, $PacName, $ClassName);

        file_put_contents($path_name, $output);

        return true;
    }


    protected function generatePackageViewIncMessages($PacVen, $PacName)
    {
        $ClassName = "msg.blade";

        $output = file_get_contents(__DIR__ . "/Stubs/GenerateViewsIncMessages.stub");
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "resources/views/inc/" . $ClassName);

        $output = $this->str_replace($output, $PacVen, $PacName, $ClassName);

        file_put_contents($path_name, $output);

        return true;
    }


    protected function generatePackageViewIncMenuForm($PacVen, $PacName)
    {
        $ClassName = "menu_form.blade";

        $output = file_get_contents(__DIR__ . "/Stubs/GenerateViewsIncMenuFormBlade.stub");
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "resources/views/inc/" . $ClassName);

        $output = $this->str_replace($output, $PacVen, $PacName, $ClassName);

        file_put_contents($path_name, $output);

        return true;
    }
    protected function generatePackageViewMenu($PacVen, $PacName)
    {
        $ClassName = "menu.blade";

        $output = file_get_contents(__DIR__ . "/Stubs/GenerateViewsMenuBlade.stub");
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "resources/views/" . $ClassName);

        $output = $this->str_replace($output, $PacVen, $PacName, $ClassName);

        file_put_contents($path_name, $output);

        return true;
    }


    protected function generatePackageAppBlade($PacVen, $PacName)
    {
        $ClassName = "app.blade";

        $output = file_get_contents(__DIR__ . "/Stubs/GenerateAppBlade.stub");
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "resources/views/layouts/" . $ClassName);

        $output = $this->str_replace($output, $PacVen, $PacName, $ClassName);

        file_put_contents($path_name, $output);

        return true;
    }


    protected function generatePackageRoutesWeb($PacVen, $PacName)
    {
        $ClassName = "web";

        $output = file_get_contents(__DIR__ . "/Stubs/GenerateRoutesWeb.stub");
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "src/routes/" . $ClassName);

        $output = $this->str_replace($output, $PacVen, $PacName, $ClassName);

        file_put_contents($path_name, $output);

        return true;
    }


    protected function generatePackageKernel($PacVen, $PacName)
    {
        $ClassName = "Kernel";

        $output = file_get_contents(__DIR__ . "/Stubs/GenerateKernel.stub");
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "src/app/Http/" . $ClassName);

        $output = $this->str_replace($output, $PacVen, $PacName, $ClassName);

        file_put_contents($path_name, $output);

        return true;
    }


    protected function generatePackageServiceProvider($PacVen, $PacName)
    {
        $ClassName = ucfirst($PacName) . "ServiceProvider";

        $output = file_get_contents(__DIR__ . "/Stubs/GenerateServiceProvider.stub");
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "src/app/Providers/" . $ClassName);

        $output = $this->str_replace($output, $PacVen, $PacName, $ClassName);

        file_put_contents($path_name, $output);

        return true;
    }

    protected function generatePackageMiddleware($PacVen, $PacName)
    {
        $ClassName = ucfirst($PacName) . "Middleware";

        $output = file_get_contents(__DIR__ . "/Stubs/GenerateMiddleware.stub");
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "src/app/Http/Middleware/" . $ClassName);

        $output = $this->str_replace($output, $PacVen, $PacName, $ClassName);

        file_put_contents($path_name, $output);

        return true;
    }

    protected function generatePackageController($PacVen, $PacName)
    {
        $ClassName = ucfirst($PacName) . "Controller";

        $output = file_get_contents(__DIR__ . "/Stubs/GenerateController.stub");
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "src/app/Http/Controllers/" . $ClassName);

        $output = $this->str_replace($output, $PacVen, $PacName, $ClassName);

        file_put_contents($path_name, $output);

        return true;
    }

    protected function generatePackageCommand($PacVen, $PacName)
    {
        $ClassName = ucfirst($PacName) . "Command";

        $output = file_get_contents(__DIR__ . "/Stubs/GenerateCommand.stub");
        $path_name = Helper::makeFileDirName("package", "packages/" .
            $PacVen . "/" .
            $PacName . "/" .
            "src/app/Console/Commands/" . $ClassName);

        $output = $this->str_replace($output, $PacVen, $PacName, $ClassName);

        file_put_contents($path_name, $output);

        return true;
    }

    protected function str_replace($output, $PacVen, $PacName, $ClassName)
    {

        $pac_ven = lcfirst(Str::snake($PacVen));
        $pac_name = lcfirst(Str::snake($PacName));

        $pac__ven = str_replace('_', '-', $pac_ven);
        $pac__name = str_replace('_', '-', $pac_name);;


        $output = str_replace('{{Vendor}}', $PacVen, $output);
        $output = str_replace('{{vendor_}}', $pac_ven, $output);
        $output = str_replace('{{vendor-}}', $pac__ven, $output);
        $output = str_replace('{{PackageName}}', $PacName, $output);
        $output = str_replace('{{package_name}}', $pac_name, $output);
        $output = str_replace('{{package-name}}', $pac__name, $output);
        $output = str_replace('{{ClassName}}', $ClassName, $output);

        return $output;
    }
}
