@extends('layouts.admin')
@section('content')
@can('scooter_create')
    <div class="block my-4">
        <a class="btn-md btn-green" href="{{ route('admin.scooters.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.price.title_singular') }}
        </a>
        <a class="btn-md btn-blue" href="{{ route('admin.scooters-excel') }}">
            Import Excel
        </a>
        <a class="btn-md btn-red" onclick="return confirm('{{ trans('global.areYouSure') }}');" href="{{ route('admin.scooters-delete-all') }}">
            Delete All
        </a>
        <button type="button" class="btn-md btn-red" data-toggle="modal" data-target="#myModal">
            Delete By Family
        </button>
    </div>
@endcan
<div class="main-card">
    <div class="header">
        {{ trans('cruds.price.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="body">
        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-Scooter">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.price.fields.id') }}
                        </th>
                        @if(!request()->is('admin/scooter*'))
                            <th>
                                {{ trans('cruds.price.fields.typename') }}
                            </th>
                        @endif
                        <th>
                            {{ trans('cruds.price.fields.itemcode') }}
                        </th>
                        <th>
                            {{ trans('cruds.price.fields.desc') }}
                        </th>
                        <th>
                            {{ trans('cruds.price.fields.price') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prices as $key => $price)
                        <tr data-entry-id="{{ $price->id }}">
                            <td align="center">
                                {{ $price->id ?? '' }}
                            </td>
                            @if(!request()->is('admin/scooter*'))
                                <td align="center">
                                    {{ $price->name ?? '' }}
                                </td>
                            @endif
                            <td align="center">
                                {{ $price->itemcode ?? '' }}
                            </td>
                            <td align="center">
                                {{ $price->description ?? '' }}
                            </td>
                            <td align="center">
                                {{ number_format($price->price, 2) ?? '' }} €
                            </td>
                            <td align="center">
                                @can('scooter_show')
                                    <a class="btn-sm btn-indigo" href="{{ route('admin.scooters.show', $price->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('scooter_edit')
                                    <a class="btn-sm btn-blue" href="{{ route('admin.scooters.edit', $price->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('scooter_delete')
                                    <form action="{{ route('admin.scooters.destroy', $price->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn-sm btn-red" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Begin -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.scooters-deletebytype') }}" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Delete By Family</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="price_id" class="text-xs required">PRICE TYPE</label>
                            <div class="form-group">
                                <select name="pricetype_id" class="{{ $errors->has('pricetype_id') ? ' is-invalid' : '' }}" required>
                                    <option value="">Choose Item</option>
                                    @foreach($pricetypes as $key => $pricetype)
                                        <option value="{{ $pricetype->id }}">
                                            {{ $pricetype->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('pricetype_id'))
                                <p class="invalid-feedback">{{ $errors->first('pricetype_id') }}</p>
                            @endif
                            <span class="block">{{ trans('cruds.pricemanage.fields.priceclass_helper') }}</span>
                        </div>
                    </div>
                    
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal End -->
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[ 0, 'asc' ]],
            pageLength: 10,
        });
        let table = $('.datatable-Scooter:not(.ajaxTable)').DataTable()
        
        
        $(".import_btn").click(function (e) {
            e.preventDefault();
            $('#selectedFile').click();
        });
        $("#selectedFile").change(function () {
            if (confirm('Are you sure ?')) {
                $("#import_form").submit()
            }
        });
    });

</script>
@endsection