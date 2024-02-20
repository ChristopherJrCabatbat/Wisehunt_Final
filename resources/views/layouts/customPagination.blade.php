   <style>
    .pagination {
        /* margin: 2px 6px 7px 0; */
        display: flex;
        justify-content: center;
        position: relative;
        bottom: 0;
        left: 5px;
        margin-top: 19px;
    }

    {{-- .pagination {
        margin: 2px 6px 7px 0;
        display: flex;
        justify-content: flex-end;
        position: fixed;
        bottom: 12px;
        left: 52%;
    } --}}

    .pagination a, .pagination span {
        display: inline-block;
        padding: 7px 8px;
        border: solid #006181 1px;
        /* background-color: #333; */
        color: black;
        border-radius: 3px;
        text-decoration: none;
        margin: 0 0 0 -3px;
    }

    .pagination a:hover {
        background-color: #d2d6da; 
    }

    .pagination .active {   
        background-color: #006181; /* Apply the theme color to the active page */
        border-color: #006181; /* Apply the theme color to the active page */
        /* border-top-color: #dee2e6;
        border-bottom-color: #dee2e6; */
        border-radius: 0;
        padding: 7px 12px;
        color: white;
        border-radius: 0;
        z-index: 1;
    }

    .pagination .disabled {
        color: #888; /* Apply a different color to disabled elements */
        pointer-events: none; /* Make disabled elements unclickable */
    }
</style>


    <div class="pagination">
        {{-- Render the "Previous" button --}}
        @if ($paginator->onFirstPage())
            {{-- <span class="disabled">&laquo; &nbsp;</span> --}}
            <span class="disabled">Previous</span>
        @else
            {{-- <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; &nbsp;</a> --}}
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
        @endif
    
        {{-- Render the page numbers --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" separator --}}
            {{-- @if (is_string($element))
                <span class="disabled">{{ $element }}</span>
            @endif --}}
    
            {{-- Array of page links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        {{-- <a href="{{ $url }}">{{ $page }}</a> --}}
                        {{-- {{ $page }} --}}
                    @endif
                @endforeach
            @endif
        @endforeach
    
        {{-- Render the "Next" button --}}
        @if ($paginator->hasMorePages())
            {{-- <a href="{{ $paginator->nextPageUrl() }}" rel="next"> &nbsp;&raquo;</a> --}}
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"> Next</a>
        @else
            {{-- <span class="disabled"> &nbsp;&raquo;</span> --}}
            <span class="disabled"> Next</span>
        @endif
    </div>

