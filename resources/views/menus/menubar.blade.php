<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{!! url('home') !!}">
            <div class="logo-img">
                <img src="{!! $company->company_logo !!}" height="50px" width="100px" class="header-brand-img" alt="LOGO">
            </div>
            {{--<span class="text">BRBHS</span>--}}
        </a>
        <button type="button" class="nav-toggle"><i data-toggle="expanded" class="ik ik-toggle-right toggle-icon"></i></button>
        <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
    </div>

    <div class="sidebar-content">
        <div class="nav-container">
            <nav id="main-menu-navigation" class="navigation-main">

                <div class="nav-item active">
                    <a href="{!! url('home') !!}"><i class="ik ik-bar-chart-2"></i><span>Dashboard </span></a>
                </div>



{{--                <p>{!! $user_permissions[0] !!}</p>--}}
                @if($role_id == 1)
                @foreach($menus as $menu)
{{--                    @if($comp_menus->contains('module_id',$menu->module_id))--}}


                        @if($menu->menu_type =='CM')

                            <div class="nav-lavel">{!! $menu->name !!}</div>

                                @foreach($menus as $main_menu)

                                    @if($main_menu->nav_label == $menu->nav_label)
                                        @if($main_menu->menu_type=='MM')

{{--                                            Main Manu--}}

                                            <div class="{!! $main_menu->div_class !!}">
                                                <a href="javascript:void(0)"><i class="{!! $main_menu->i_class !!}"></i><span>{!! $main_menu->name !!}</span></a>

                                                @foreach($menus as $sub)

                                                    @if($main_menu->menu_prefix == $sub->menu_prefix)
                                                        @if($sub->menu_type=='SM')

{{--                                                            SUB MENU--}}
{{----}}
                                                            <div class="submenu-content">
                                                                <a href="{!! url(''.$sub->url.'') !!}" class="menu-item">{!! $sub->name !!}</a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif

                                @endforeach
                        @endif
{{--                        @endif--}}
                @endforeach
                @endif




                @if($role_id == 2)
                    @foreach($menus->where('module_id',2) as $menu)
                        @if($comp_menus->contains('module_id',$menu->module_id))


                            @if($menu->menu_type =='CM')

                                <div class="nav-lavel">{!! $menu->name !!}</div>

                                @foreach($menus as $main_menu)

                                    @if($main_menu->nav_label == $menu->nav_label)
                                        @if($main_menu->menu_type=='MM')

                                            {{--                                            Main Manu--}}

                                            <div class="{!! $main_menu->div_class !!}">
                                                <a href="javascript:void(0)"><i class="{!! $main_menu->i_class !!}"></i><span>{!! $main_menu->name !!}</span></a>

                                                @foreach($menus as $sub)

                                                    @if($main_menu->menu_prefix == $sub->menu_prefix)
                                                        @if($sub->menu_type=='SM')

                                                            {{--                                                            SUB MENU--}}
                                                            {{----}}
                                                            <div class="submenu-content">
                                                                <a href="{!! url(''.$sub->url.'') !!}" class="menu-item">{!! $sub->name !!}</a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif

                                @endforeach
                            @endif
                        @endif
                    @endforeach
                @endif





                @if($role_id == 3)

                    @foreach($user_permissions as $menu)
                        @if($menu['menu_type'] == 'CM')

                            <div class="nav-lavel">{!! $menu['name'] !!}</div>

                            @foreach($user_permissions as $main_menu)

                                @if($main_menu['nav_label'] == $menu['nav_label'])
                                    @if($main_menu['menu_type'] == 'MM')

                                        {{--Main Manu--}}

                                        <div class="{!! $main_menu['div_class'] !!}">
                                            <a href="javascript:void(0)"><i class="{!! $main_menu['i_class'] !!}"></i><span>{!! $main_menu['name'] !!}</span></a>

                                            @foreach($user_permissions as $sub)

                                                @if($main_menu['menu_prefix'] == $sub['menu_prefix'])
                                                    @if($sub['menu_type']=='SM')

                                                        {{--SUB MENU--}}

                                                        <div class="submenu-content">
                                                            <a href="{!! url(''.$sub['url'].'') !!}" class="menu-item">{!! $sub['name'] !!}</a>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @endif

                            @endforeach
                        @endif
                    @endforeach
                @endif




            </nav>
        </div>
    </div>
</div>
