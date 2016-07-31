<header class="main-header">

	<a href="{{ url('/') }}" class="logo">
		<span class="logo-mini">Andila</span>
		<span class="logo-lg">Andila</span>
	</a>

	<nav class="navbar navbar-static-top" role="navigation">
		
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
      	</a>

      	<div class="navbar-custom-menu">
        	<ul class="nav navbar-nav">

            <li>
              <a href="{{ route('web.docs.read', [strtolower($user->handling), 'overview']) }}" data-toggle="tooltip" data-placement="bottom" title="Panduan">
                <i class="fa fa-question-circle"></i>
              </a>
            </li>

        		<!-- User -->
        		<li class="dropdown user user-menu">
        			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
          			<img src="{{ asset('img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
          			<span class="hidden-xs">{{ $user->email }}</span>
          		</a>
    					<ul class="dropdown-menu">

    						<li class="user-header">
    							<img src="{{ asset('img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
    							<p>
    								{{ $user->email }}
    								<small>{{ $user->handling }}</small>
    							</p>
    						</li>

    						<li class="user-body">
    							<div class="row">
    								<a href="@unless($user->isAdmin()) {{ route('web.'. strtolower($user->handling). 's.show', $user->handleable->id) }} @else {{ url('#') }}  @endunless" class="col-xs-6 text-center">
    									Profil
    								</a>
    								<a href="{{ route('web.auth.logout') }}" class="col-xs-6 text-center">
    									Logout
    								</a>
    							</div>
    						</li>

    					</ul>
        		</li>		
        		<!-- /User -->

          		<!-- Control >
          		<li>
          			<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          		</li>
          		<! /Control -->
          	</ul>
        </div>
    </nav>
</header>