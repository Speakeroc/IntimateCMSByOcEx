@if($paginator->lastPage() > 1)
    <div class="row d-flex align-items-center">
        <div class="col-sm-12 col-md-5 d-flex">
            <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">
                {{ __('lang.page') }} <strong>{{ $paginator->currentPage() }}</strong> {{ __('lang.page_of') }} <strong>{{ $paginator->lastPage() }}</strong>
            </div>
        </div>
        <div class="col-sm-12 col-md-7 d-flex justify-content-end">
            <div>
                <ul class="pagination pagination-sm m-0">
                    @if ($paginator->onFirstPage())
                        <li class="paginate_button page-item previous disabled"><span class="page-link"><i class="fa fa-angle-left"></i></span></li>
                    @else
                        <li class="paginate_button page-item previous"><a href="{{ $paginator->previousPageUrl() }}" class="page-link"><i class="fa fa-angle-left"></i></a></li>
                    @endif
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <li class="paginate_button page-item disabled"><span class="page-link">{{ $element }}</span></li>
                        @endif
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="paginate_button page-item active"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="paginate_button page-item"><a href="{{ $url }}" class="page-link">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                    @if ($paginator->hasMorePages())
                        <li class="paginate_button page-item next"><a href="{{ $paginator->nextPageUrl() }}" class="page-link"><i class="fa fa-angle-right"></i></a></li>
                    @else
                        <li class="paginate_button page-item disabled"><a href="#" class="page-link"><i class="fa fa-angle-right"></i></a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endif
