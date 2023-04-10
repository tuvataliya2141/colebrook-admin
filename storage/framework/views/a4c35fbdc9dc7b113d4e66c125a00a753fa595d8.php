<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="d-block text-center">
                <?php if(get_setting('system_logo_white') != null): ?>
                    <img class="mw-100" src="<?php echo e(uploaded_asset(get_setting('system_logo_white'))); ?>" class="brand-icon" alt="<?php echo e(get_setting('site_name')); ?>">
                <?php else: ?>
                    <img class="mw-100" src="<?php echo e(static_asset('assets/img/logo.png')); ?>" class="brand-icon" alt="<?php echo e(get_setting('site_name')); ?>">
                <?php endif; ?>
            </a>
        </div>
        <div class="aiz-side-nav-wrap">
            <div class="px-20px mb-3">
                <input class="form-control bg-soft-secondary border-0 form-control-sm text-white" type="text" name="" placeholder="<?php echo e(translate('Search in menu')); ?>" id="menu-search" onkeyup="menuSearch()">
            </div>
            <ul class="aiz-side-nav-list" id="search-menu">
            </ul>
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="aiz-side-nav-link">
                        <i class="las la-home aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Dashboard')); ?></span>
                    </a>
                </li>

                <!-- Product -->
                <?php if(Auth::user()->user_type == 'admin' || in_array('2', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Products')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link" href="<?php echo e(route('products.create')); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Add New product')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('products.all')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('All Products')); ?></span>
                                </a>
                            </li>
                            
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('product_bulk_upload.index')); ?>" class="aiz-side-nav-link" >
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Bulk Import')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('product_bulk_export.index')); ?>" class="aiz-side-nav-link" download>
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Bulk Export')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('categories.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['categories.index', 'categories.create', 'categories.edit'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Category')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('brands.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['brands.index', 'brands.create', 'brands.edit'])); ?>" >
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Brand')); ?></span>
                                </a>
                            </li>
                            

                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('attributes.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['attributes.index','attributes.create','attributes.edit'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Attribute')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('colors')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['attributes.index','attributes.create','attributes.edit'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Colors')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <!-- Code cange by Tarun on 02-fab-22 CR#2 - start -->
                                <a href="<?php echo e(route('reviews.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['reviews.index','reviews.create','reviews.edit'])); ?>">
                                <!-- Code cange by Tarun on 02-fab-22 CR#2 - end -->
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Product Reviews')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('products.products_count_list_by_user')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['products.products_count_list_by_user','products.products_view_by_user'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Product view by total user')); ?></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Customers -->
                <?php if(Auth::user()->user_type == 'admin' || in_array('8', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-user-friends aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Customers')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('customers.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Customer list')); ?></span>
                                </a>
                            </li>
                            <?php if(get_setting('classified_product') == 1): ?>
                                <li class="aiz-side-nav-item">
                                    <a href="<?php echo e(route('classified_products')); ?>" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('Classified Products')); ?></span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="<?php echo e(route('customer_packages.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['customer_packages.index', 'customer_packages.create', 'customer_packages.edit'])); ?>">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('Classified Packages')); ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <!-- Sale -->
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-money-bill aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text"><?php echo e(translate('Orders')); ?></span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        <?php if(Auth::user()->user_type == 'admin' || in_array('3', json_decode(Auth::user()->staff->role->permissions))): ?>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('all_orders.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['all_orders.index', 'all_orders.show'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('All Orders')); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if(Auth::user()->user_type == 'admin' || in_array('4', json_decode(Auth::user()->staff->role->permissions))): ?>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('inhouse_orders.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['inhouse_orders.index', 'inhouse_orders.show'])); ?>" >
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Inhouse orders')); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if(Auth::user()->user_type == 'admin' || in_array('6', json_decode(Auth::user()->staff->role->permissions))): ?>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('pick_up_point.order_index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['pick_up_point.order_index','pick_up_point.order_show'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Pick-up Point Order')); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
                
                <?php if(Auth::user()->user_type == 'admin' || in_array('22', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="<?php echo e(route('uploaded-files.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['uploaded-files.create'])); ?>">
                            <i class="las la-folder-open aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Uploaded Files')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                  <!-- marketing -->
                <?php if(Auth::user()->user_type == 'admin' || in_array('11', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-bullhorn aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Marketing')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <?php if(Auth::user()->user_type == 'admin' || in_array('7', json_decode(Auth::user()->staff->role->permissions))): ?>
                                <li class="aiz-side-nav-item">
                                    <a href="<?php echo e(route('newsletters.index')); ?>" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('Newsletters')); ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('subscribers.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Subscribers')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('coupon.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['coupon.index','coupon.create','coupon.edit'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Coupon')); ?></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                
               <!-- Reports -->
                <?php if(Auth::user()->user_type == 'admin' || in_array('10', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-file-alt aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Reports')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('in_house_sale_report.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['in_house_sale_report.index'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('In House Product Sale')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('stock_report.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['stock_report.index'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Products Stock')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('wish_report.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['wish_report.index'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Products wishlist')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('user_search_report.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['user_search_report.index'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('User Searches')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('commission-log.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Commission History')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('wallet-history.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Wallet Recharge History')); ?></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
          
            <!-- Support -->
                <?php if(Auth::user()->user_type == 'admin' || in_array('12', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-link aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Support')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <?php if(Auth::user()->user_type == 'admin' || in_array('12', json_decode(Auth::user()->staff->role->permissions))): ?>
                                <?php
                                    $support_ticket = DB::table('tickets')
                                                ->where('viewed', 0)
                                                ->select('id')
                                                ->count();
                                ?>
                                <li class="aiz-side-nav-item">
                                    <a href="<?php echo e(route('support_ticket.admin_index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['support_ticket.admin_index', 'support_ticket.admin_show'])); ?>">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('Ticket')); ?></span>
                                        <?php if($support_ticket > 0): ?><span class="badge badge-info"><?php echo e($support_ticket); ?></span><?php endif; ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php
                                $conversation = \App\Models\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', '1')->get();
                            ?>
                            <?php if(Auth::user()->user_type == 'admin' || in_array('12', json_decode(Auth::user()->staff->role->permissions))): ?>
                                <li class="aiz-side-nav-item">
                                    <a href="<?php echo e(route('conversations.admin_index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['conversations.admin_index', 'conversations.admin_show'])); ?>">
                                        <span class="aiz-side-nav-text"><?php echo e(translate('Product Queries')); ?></span>
                                        <?php if(count($conversation) > 0): ?>
                                            <span class="badge badge-info"><?php echo e(count($conversation)); ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

            <!-- Website Setup -->
                <?php if(Auth::user()->user_type == 'admin' || in_array('13', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['website.footer', 'website.header', 'website.home_card'])); ?>" >
                            <i class="las la-desktop aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Website Setup')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('website.header')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Header')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('website.footer', ['lang'=>  App::getLocale()] )); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['website.footer'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Footer')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('website.home_card', ['lang'=>  App::getLocale()] )); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['website.home_card'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Home Card')); ?></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

            <!-- Setup & Configurations -->
                <?php if(Auth::user()->user_type == 'admin' || in_array('14', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-dharmachakra aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Setup & Configurations')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">

                            <!--changes-->
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('general_setting.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('General Settings')); ?></span>
                                </a>
                            </li>
                            
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('location.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('PIN Code wise Delivery')); ?></span>
                                </a>
                            </li>
                            
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('activation.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Features activation')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('business_setting')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Business Settings')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('languages.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['languages.index', 'languages.create', 'languages.store', 'languages.show', 'languages.edit'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Languages')); ?></span>
                                </a>
                            </li>

                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('currency.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Currency')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('tax.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['tax.index', 'tax.create', 'tax.store', 'tax.show', 'tax.edit'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Vat & TAX')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('pick_up_points.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['pick_up_points.index','pick_up_points.create','pick_up_points.edit'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Pickup point')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('smtp_settings.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('SMTP Settings')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('payment_method.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Payment Methods')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('file_system.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('File System & Cache Configuration')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('social_login.index')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Social media Logins')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('home-banner.list')); ?>" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Home Banner')); ?></span>
                                </a>
                            </li>

                            <li class="aiz-side-nav-item">
                                <a href="javascript:void(0);" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Facebook')); ?></span>
                                    <span class="aiz-side-nav-arrow"></span>
                                </a>
                                <ul class="aiz-side-nav-list level-3">
                                    <li class="aiz-side-nav-item">
                                        <a href="<?php echo e(route('facebook_chat.index')); ?>" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('Facebook Chat')); ?></span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="<?php echo e(route('facebook-comment')); ?>" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('Facebook Comment')); ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="aiz-side-nav-item">
                                <a href="javascript:void(0);" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Google')); ?></span>
                                    <span class="aiz-side-nav-arrow"></span>
                                </a>
                                <ul class="aiz-side-nav-list level-3">
                                    <li class="aiz-side-nav-item">
                                        <a href="<?php echo e(route('google_analytics.index')); ?>" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('Analytics Tools')); ?></span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="<?php echo e(route('google_recaptcha.index')); ?>" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('Google reCAPTCHA')); ?></span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="<?php echo e(route('google-map.index')); ?>" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('Google Map')); ?></span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="<?php echo e(route('google-firebase.index')); ?>" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('Google Firebase')); ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>




                            <li class="aiz-side-nav-item">
                                <a href="javascript:void(0);" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Shipping')); ?></span>
                                    <span class="aiz-side-nav-arrow"></span>
                                </a>
                                <ul class="aiz-side-nav-list level-3">
                                    <li class="aiz-side-nav-item">
                                        <a href="<?php echo e(route('shipping_configuration.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])); ?>">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('Shipping Configuration')); ?></span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="<?php echo e(route('countries.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['countries.index','countries.edit','countries.update'])); ?>">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('Shipping Countries')); ?></span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="<?php echo e(route('states.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['states.index','states.edit','states.update'])); ?>">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('Shipping States')); ?></span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="<?php echo e(route('cities.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['cities.index','cities.edit','cities.update'])); ?>">
                                            <span class="aiz-side-nav-text"><?php echo e(translate('Shipping Cities')); ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </li>
                <?php endif; ?>


            <!-- Staffs -->
                <?php if(Auth::user()->user_type == 'admin' || in_array('20', json_decode(Auth::user()->staff->role->permissions))): ?>
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-user-tie aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text"><?php echo e(translate('Staffs')); ?></span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('staffs.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('All staffs')); ?></span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="<?php echo e(route('roles.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])); ?>">
                                    <span class="aiz-side-nav-text"><?php echo e(translate('Staff permissions')); ?></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
<?php /**PATH /home/u985417933/domains/colebrooknow.com/public_html/admin/resources/views/backend/inc/admin_sidenav.blade.php ENDPATH**/ ?>