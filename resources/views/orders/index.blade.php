@extends("template.app")
@section("content")
    <div class="container py-4" style="width: 100%">
        <header class="d-flex align-items-end" style="width: 100%">
            <div>
                <h3 class="mb-1">Orders</h3>
                <span class="text-muted">
                    List of orders that had been placed by the users.
                </span>
            </div>
            <div class="d-flex gap-3 ms-auto">
                <form action="" method="GET" class="d-flex gap-2 ms-auto">
                    <div class="input-group">
                        <span class="input-group-text">Search</span>
                        <input
                            type="text"
                            class="form-control"
                            id="search"
                            name="search"
                            value="{{ $search_query }}"
                            autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Search
                    </button>
                </form>
            </div>
        </header>
        <main class="d-flex flex-column gap-4 py-4" style="width: 100%">
            <div class="d-flex flex-column gap-2">
                @foreach ($data['orders']->items() as $idx => $order)
                    <x-order-list-item :order="$order" />
                @endforeach
            </div>
            {{ $data['orders']->links() }}
        </main>
    </div>
@endsection
