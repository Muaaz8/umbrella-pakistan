<div class="container search-location">
    <div class="row">
        <div class="col-12 mb-5">
            <div id="search-form" class="search-form js-search-form">
                <form class="form-search" role="search">
                    <div class="row">
                        <div class="col-3 form-group p-1">
                            <select class="form-control pagesSearchBarSelect" name="category_id"
                                style="width:100% !important">
                                <option value="0" default selected>All Categories</option>
                                @foreach ($data['searchDropdown'] as $item)
                                    <option value="{{ $item->id }}">{{ $item->product_parent_category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-9 form-group p-1">
                            <input type="text" class="form-control pagesSearchBarInput"
                                data-type="{{ $data['url_type'] }}" data-category-id="0"
                                placeholder="What are you looking for?" />
                        </div>
                    </div>
                </form>
                <div class="instant-results">
                    <ul class="list-unstyled result-bucket" style="height: 50vh;overflow-x: hidden;overflow-y: scroll;">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /*---------- Search ----------*/
    .result-bucket li {
        padding: 7px 15px;
    }

    .pagesSearchBarInput,
    .pagesSearchBarSelect {
        border: 1.5px solid #08295a !important;
    }

    .pagesSearchBarInput:focus,
    .pagesSearchBarSelect:focus {
        border: 2px solid #08295a !important;
    }

    .instant-results {
        background: #fff;
        width: 85%;
        color: gray;
        position: absolute;
        top: 100%;
        left: 16%;
        border: 1px solid rgba(0, 0, 0, .15);
        border-radius: 4px;
        -webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, .175);
        box-shadow: 0 2px 4px rgba(0, 0, 0, .175);
        display: none;
        z-index: 9;
    }

    .form-search {
        transition: all 200ms ease-out;
        -webkit-transition: all 200ms ease-out;
        -ms-transition: all 200ms ease-out;
    }

    .search-form {
        position: relative;
        max-width: 100%;
    }

    .result-link {
        color: #4f7593;
    }

    .result-link .media-body {
        font-size: 13px;
        color: gray;
        padding-left: 13px;
    }

    .result-link .media-heading {
        font-size: 15px;
        font-weight: 400;
        color: #4f7593;
    }

    .result-link:hover,
    .result-link:hover .media-heading,
    .result-link:hover .media-body {
        text-decoration: none;
        color: #4f7593
    }

    .result-link .media-object {
        width: 50px;
        padding: 3px;
        border: 1px solid #c1c1c1;
        border-radius: 3px;
    }

    .result-entry+.result-entry {
        border-top: 1px solid #ddd;
    }

    .top-keyword {
        margin: 3px 0 0;
        font-size: 12px;
        font-family: Arial;
    }

    .top-keyword a {
        font-size: 12px;
        font-family: Arial;
    }

    .top-keyword a:hover {
        color: rgba(0, 0, 0, 0.7);
    }

</style>

@section('script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.pagesSearchBarSelect').select2({
                placeholder: 'All Categories',
            });
        });
    </script>
@endsection
