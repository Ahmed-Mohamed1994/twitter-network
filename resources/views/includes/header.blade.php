<header>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                @if(Auth::user())
                    <a class="navbar-brand" href="{{ route('dashboard') }}">Dashboard</a>
                @endif
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                @if(Auth::user())
                    <ul class="nav navbar-nav">
                        <li @if(Request::path() == 'news_feed') class="active" @endif><a
                                    href="{{ route('news-feed') }}">News
                                Feed</a></li>
                        <li @if(Request::path() == 'activity_feed') class="active" @endif><a
                                    href="{{ route('activity-feed') }}">Activity Feed</a></li>
                        <li @if(Request::path() == 'search-algolia') class="active" @endif><a
                                    href="{{ route('get.search.algolia') }}">Search Algolia</a></li>
                    </ul>
                @endif
                <ul class="nav navbar-nav navbar-right">
                    @if(Auth::user())
                        <li><a href="{{ route('account') }}">Account</a></li>
                        <li><a href="{{ route('logout') }}">Logout</a></li>
                    @endif
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</header>