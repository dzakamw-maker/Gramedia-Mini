<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- BEGIN NAVBAR LOGO -->
        <a href="/" aria-label="Tabler" class="navbar-brand navbar-brand-autodark me-3">

            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1cWzAkEdbaCzFQvp6GoJdo2JnNoIYuIibKIpaxoOTvQ&s=10"
                class="navbar-brand-image">
            Books
        </a>
        <!-- END NAVBAR LOGO -->
        <ul class="navbar-nav mx-auto w-50">

            @if (Auth::check() && Auth::user()->role == 'admin')

                {{-- MENU KHUSUS ADMIN --}}
                <li class="nav-item active">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.book-categories.index') }}"
                        class="nav-link {{ request()->routeIs('admin.book-categories.index') ? 'active' : '' }}">
                        Kategori Buku
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.subscription-packages.index') }}" class="nav-link">
                        Paket Langganan
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.books.index') }}"
                        class="nav-link {{ request()->routeIs('admin.books.index') ? 'active' : ''}}">
                        Buku
                    </a>
                </li>

            @else

                {{-- KATEGORI --}}
                <div class="dropdown mt-2 me-2">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Kategori
                    </button>

                    <div class="dropdown-menu dropdown-menu-card" style="min-width: 600px">
                        <div class="p-3">
                            <div class="row g-2">
                                @foreach($navbarCategories as $category)
                                    <div class="col-3">
                                        <a href="{{ route('category.books', $category->id) }}" class="text-decoration-none text-dark">
                                            <div class="card py-2 card-link">
                                                <div class="card-body p-2 text-center">
                                                    {{ $category->name }}
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{--- SEARCH ---}}
                <form method="GET" action="{{ route('search') }}" class="input-icon w-100 py-2">
                    <input type="text" value="{{ request('q') }}" name="q" class="form-control form-control-rounded"
                        placeholder="Cari judul, produk, buku, penulis..." />
                    <button type="submit" class="input-icon-addon border-0 p-0 bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true" focusable="false" class="icon">
                            <path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                            <path d="M21 21l-6 -6" />
                        </svg>
                    </button>
                </form>

                {{-- CART ICON --}}
                <div class="mt-3 ms-2">
                    <i class="fa-solid fa-cart-arrow-down fs-3 text-dark"></i>
                </div>

            @endif

        </ul>

        <div class="flex-row-order-md_last ms_auto">
            <div class="flex-now order-md-last ms-auto">
                @if(Auth::check())
                    <a href="{{route('logout')}}" class="btn btn-danger">Log Out</a>
                @else
                    <a href="{{route('login') }}" class="btn btn-primary">Masuk</a>
                    <a href="{{route('register')}}" class="btn btn-light">Daftar</a>
                @endif
            </div>
        </div>
</header>