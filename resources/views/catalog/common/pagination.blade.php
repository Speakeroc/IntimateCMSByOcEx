@if ($paginator->lastPage() > 1)
    <div class="row d-flex align-items-center">
        <div id="pagination" class="col-12 d-flex justify-content-center">
            <ul class="pagination m-0">
                @if ($paginator->onFirstPage())
                    <li class="page-item previous disabled"><span class="page-link"><i class="fa fa-angle-left"></i></span></li>
                @else
                    <li class="page-item previous"><a href="{{ $paginator->previousPageUrl() }}" class="page-link" aria-label="Preview page"><i class="fa fa-angle-left"></i></a></li>
                @endif
                @php
                @endphp
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a href="{{ $url }}" class="page-link">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                @if ($paginator->hasMorePages())
                    <li class="page-item next"><a href="{{ $paginator->nextPageUrl() }}" class="page-link" aria-label="Next page"><i class="fa fa-angle-right"></i></a></li>
                @else
                    <li class="page-item disabled"><span class="page-link"><i class="fa fa-angle-right"></i></span></li>
                @endif
            </ul>
        </div>
    </div>
@endif
