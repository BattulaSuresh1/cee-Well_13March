<section>
        <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
    
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="{{ $menu == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <i class="material-icons">home</i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('register') }}">
                        <i class="material-icons">person_add</i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="{{ $menu == 'role' ? 'active' : '' }}">
                    <a href="{{ url('role/list') }}">
                        <i class="material-icons">layers</i>
                        <span>Roles</span>
                    </a>
                </li>
                <li >
                    <a href="{{ url('feature')}}">
                        <i class="material-icons">home</i>
                        <span>Customer Master</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('feature')  }}">
                        <i class="material-icons">text_fields</i>
                        <span>Supplier Master</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('feature') }}">
                        <i class="material-icons">layers</i>
                        <span>Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('feature') }}">
                        <i class="material-icons">layers</i>
                        <span>Price List</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('feature') }}">
                        <i class="material-icons">layers</i>
                        <span>Stock</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('feature') }}">
                        <i class="material-icons">layers</i>
                        <span>Prescription</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('feature') }}">
                        <i class="material-icons">layers</i>
                        <span>Shopping Cart</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ url('feature') }}">
                        <i class="material-icons">layers</i>
                        <span>Sales Order</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ url('feature') }}">
                        <i class="material-icons">layers</i>
                        <span>Invoice</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ url('feature') }}">
                        <i class="material-icons">layers</i>
                        <span>Return</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ url('feature') }}">
                        <i class="material-icons">layers</i>
                        <span>Credit Note</span>
                    </a>
                </li>
                  <li>
                    <a href="{{ url('feature') }}">
                        <i class="material-icons">layers</i>
                        <span>Return</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ url('feature') }}">
                        <i class="material-icons">layers</i>
                        <span>Customer Payments</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- #Menu -->
        
    </aside>
    <!-- #END# Left Sidebar -->
    <!-- Right Sidebar -->
    <aside id="rightsidebar" class="right-sidebar">
        <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>
            <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                <ul class="demo-choose-skin">
                    <li data-theme="red" class="active">
                        <div class="red"></div>
                        <span>Red</span>
                    </li>
                    <li data-theme="pink">
                        <div class="pink"></div>
                        <span>Pink</span>
                    </li>
                    <li data-theme="purple">
                        <div class="purple"></div>
                        <span>Purple</span>
                    </li>
                    <li data-theme="deep-purple">
                        <div class="deep-purple"></div>
                        <span>Deep Purple</span>
                    </li>
                    <li data-theme="indigo">
                        <div class="indigo"></div>
                        <span>Indigo</span>
                    </li>
                    <li data-theme="blue">
                        <div class="blue"></div>
                        <span>Blue</span>
                    </li>
                    <li data-theme="light-blue">
                        <div class="light-blue"></div>
                        <span>Light Blue</span>
                    </li>
                    <li data-theme="cyan">
                        <div class="cyan"></div>
                        <span>Cyan</span>
                    </li>
                    <li data-theme="teal">
                        <div class="teal"></div>
                        <span>Teal</span>
                    </li>
                    <li data-theme="green">
                        <div class="green"></div>
                        <span>Green</span>
                    </li>
                    <li data-theme="light-green">
                        <div class="light-green"></div>
                        <span>Light Green</span>
                    </li>
                    <li data-theme="lime">
                        <div class="lime"></div>
                        <span>Lime</span>
                    </li>
                    <li data-theme="yellow">
                        <div class="yellow"></div>
                        <span>Yellow</span>
                    </li>
                    <li data-theme="amber">
                        <div class="amber"></div>
                        <span>Amber</span>
                    </li>
                    <li data-theme="orange">
                        <div class="orange"></div>
                        <span>Orange</span>
                    </li>
                    <li data-theme="deep-orange">
                        <div class="deep-orange"></div>
                        <span>Deep Orange</span>
                    </li>
                    <li data-theme="brown">
                        <div class="brown"></div>
                        <span>Brown</span>
                    </li>
                    <li data-theme="grey">
                        <div class="grey"></div>
                        <span>Grey</span>
                    </li>
                    <li data-theme="blue-grey">
                        <div class="blue-grey"></div>
                        <span>Blue Grey</span>
                    </li>
                    <li data-theme="black">
                        <div class="black"></div>
                        <span>Black</span>
                    </li>
                </ul>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="settings">
                <div class="demo-settings">
                    <p>GENERAL SETTINGS</p>
                    <ul class="setting-list">
                        <li>
                            <span>Report Panel Usage</span>
                            <div class="switch">
                                <label><input type="checkbox" checked><span class="lever"></span></label>
                            </div>
                        </li>
                        <li>
                            <span>Email Redirect</span>
                            <div class="switch">
                                <label><input type="checkbox"><span class="lever"></span></label>
                            </div>
                        </li>
                    </ul>
                    <p>SYSTEM SETTINGS</p>
                    <ul class="setting-list">
                        <li>
                            <span>Notifications</span>
                            <div class="switch">
                                <label><input type="checkbox" checked><span class="lever"></span></label>
                            </div>
                        </li>
                        <li>
                            <span>Auto Updates</span>
                            <div class="switch">
                                <label><input type="checkbox" checked><span class="lever"></span></label>
                            </div>
                        </li>
                    </ul>
                    <p>ACCOUNT SETTINGS</p>
                    <ul class="setting-list">
                        <li>
                            <span>Offline</span>
                            <div class="switch">
                                <label><input type="checkbox"><span class="lever"></span></label>
                            </div>
                        </li>
                        <li>
                            <span>Location Permission</span>
                            <div class="switch">
                                <label><input type="checkbox" checked><span class="lever"></span></label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </aside>
    <!-- #END# Right Sidebar -->
</section>
