<header id="page-header">
    <div class="content-header">
        <div class="space-x-1">
            <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="sidebar_toggle">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>

        <div class="space-x-1">
            <a href="{{ route('seeders') }}" class="btn btn-alt-secondary">
                <i class="fa-solid fa-rss"></i>
            </a>
            <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                <i class="far fa-fw fa-list-alt"></i>
            </button>
            <a href="{{ route('client.index') }}" target="_blank" class="btn btn-alt-secondary">
                <i class="fa-solid fa-house"></i>
            </a>
        </div>
    </div>
</header>
