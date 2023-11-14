<style>
    .pagination {
        margin: 10px 0;
        text-align: center;
        /* Center the pagination links */
        position: fixed;
        bottom: 12px;
        left: 53%;
    }

    .pagination a,
    .pagination span {
        display: inline-block;
        padding: 5px 8px;
        background-color: #006181;
        /* Apply the theme color here */
        color: white;
        border-radius: 3px;
        text-decoration: none;
        margin-right: 5px;
        Add some spacing between links
    }

    .pagination a:hover {
        background-color: #363535;
        /* Apply the theme color here on hover */
    }

    .pagination .active {
        background-color: #006181;
        /* Apply the theme color to the active page */
    }

    .pagination .disabled {
        color: white;
        /* Apply a different color to disabled elements */
        pointer-events: none;
        /* Make disabled elements unclickable */
    }
</style>


<div class="pagination">
    {{-- Render the "Previous" button --}}
    @if ($paginator->onFirstPage())
        <span class="disabled">&laquo; &nbsp;</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; &nbsp;</a>
    @endif

    {{-- Render the page numbers --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" separator --}}
        @if (is_string($element))
            <span class="disabled">{{ $element }}</span>
        @endif

        {{-- Array of page links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    {{-- <a href="{{ $url }}">{{ $page }}</a> --}}
                    {{ $page }}
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Render the "Next" button --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next"> &nbsp;&raquo;</a>
    @else
        <span class="disabled"> &nbsp;&raquo;</span>
    @endif
</div>
