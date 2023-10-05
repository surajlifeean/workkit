<?php $plugins = \Nwidart\Modules\Facades\Module::allEnabled(); ?>
<div class="side-content-wrap">
    <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
        <ul class="navigation-left accordion mt-3" id="accordionExample">
            <li class=" {{ request()->is('dashboard/admin') ? 'active' : '' }}">
                <a class="" href="/dashboard/admin">
                    <i class="nav-icon i-Bar-Chart"></i>
                    <span class="">{{ __('translate.Dashboard') }}</span>
                    
                </a>
            </li>
            <li class="">
                <a href="javascript:void(0)" class="" style="padding: 1rem 0rem;display: inline-block;width: 10rem;background: #000;border-radius: 1rem;" aria-expanded="true" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                    <i class="ti-dashboard"></i><span>dashboard</span>
                </a>
                <ul id="collapse1" class="submenu list-unstyled collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <li>
                        dhhdhh
                    </li>
                </ul>
            </li>
           
        </ul>
    </div>
</div>