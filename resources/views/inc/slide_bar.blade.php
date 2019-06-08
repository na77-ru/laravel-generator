{{--<div class="sidebar-header">--}}
{{--    <h3>Bootstrap Sidebar</h3>--}}
{{--</div>--}}


{{--                    <li> <a href="{{route('generator_menu')}}">{{__('menu')}}</a> </li> --}}
{{--                    <li> <a href="{{route('show_alex_claimer_generator_config')}}">{{__('config')}}</a> </li> --}}
{{--                    <li> <a href="{{route('generator_create_migration')}}">{{__('migration')}}</a> </li> --}}
{{--                    <li> <a href="{{route('generator_create_seeders')}}">{{__('seeders')}}</a> </li> --}}

<ul class="list-unstyled components">
    <li class="active">
        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            {{ __('Menu') }}
        </a>
        <ul class="collapse list-unstyled" id="homeSubmenu">

            <li><a href="{{route('generator_menu')}}">{{__('menu')}}</a></li> 
            <li><a href="{{route('show_alex_claimer_generator_config')}}">{{__('config')}}</a></li> 
            <li><a href="{{route('generator_create_migration')}}">{{__('migration')}}</a></li> 
            <li><a href="{{route('generator_create_seeders')}}">{{__('seeders')}}</a></li> 

        </ul>
    </li>
    <li><a href="{{route('generator_menu')}}">{{__('menu')}}</a></li>
    <li><a href="{{route('show_alex_claimer_generator_config')}}">{{__('config')}}</a></li>
    <li><a href="{{route('generator_create_migration')}}">{{__('migration')}}</a></li>
    <li><a href="{{route('generator_create_seeders')}}">{{__('seeders')}}</a></li>
</ul>
