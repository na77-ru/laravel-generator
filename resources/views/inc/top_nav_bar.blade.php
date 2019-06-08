<li class="nav-item dropdown">
    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        {{ __('Menu')}} <span class="caret"></span>
    </a>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('generator_menu') }}">
            {{ __('menu') }}
        </a>
        <a class="dropdown-item" href="{{ route('show_alex_claimer_generator_config') }}">
            {{ __('config') }}
        </a>
        <a class="dropdown-item" href="{{ route('generator_create_migration') }}">
            {{ __('migration') }}
        </a>
        <a class="dropdown-item" href="{{ route('generator_create_seeders') }}">
            {{ __('seeders') }}
        </a>


    </div>
</li>
