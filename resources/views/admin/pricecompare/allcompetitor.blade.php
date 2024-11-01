@extends('layouts.admin')
@section('content')
<div class="block my-4">
    <button type="button" class="btn-md btn-blue" data-toggle="modal" data-target="#printpdfmodal">
        Print PDF
    </button>
</div>
<div class="main-card">
    <div class="header">
        {{ trans('cruds.price.title_singular') }} {{ trans('global.list') }} All Comparation
    </div>

    <div class="body">
        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-Pricecompare" style="overflow-x: scroll; display: block;">
                <thead>
                    <tr>
                        <th style="text-align: center;" rowspan="2">
                            {{ trans('cruds.price.fields.id') }}
                        </th>
                        <th style="text-align: center;" rowspan="2">
                            {{ trans('cruds.price.fields.itemcode') }}
                        </th>
                        <th style="text-align: center;" rowspan="2">
                            {{ trans('cruds.price.fields.desc') }}
                        </th>
                        <th style="text-align: center;" rowspan="2">
                            {{ trans('cruds.price.fields.price') }}
                        </th>
                        @foreach($pricecompetitors as $key => $pricecompetitor)
                            <th style="text-align: center;" colspan="2">
                                {{ $pricecompetitor->name }}
                            </th>
                        @endforeach
                        <th style="text-align: center;" rowspan="2">
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        @foreach($pricecompetitors as $key => $pricecompetitor)
                            <th style="text-align: center;">
                                DESCRIPTION
                            </th>
                            <th style="text-align: center;">
                                PRICE
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($prices as $key => $price)
                        <tr data-entry-id="{{ $price->id }}">
                            <td align="center">
                                {{ $loop->index + 1 }}
                            </td>
                            <td align="center">
                                {{ $price->itemcode ?? '' }}
                            </td>
                            <td align="center">
                                {{ $price->description ?? '' }}
                            </td>
                            <td align="center">
                                {{ number_format($price->price, 2) ?? '' }} €
                            </td>
                            @php
                                $num_var = 0;
                            @endphp
                            @foreach($pricecompetitors as $keys => $pricecompetitor)
                                @php
                                    $compares_num = 0;
                                @endphp
                                @foreach($pricecompares as $keyss => $pricecompare)
                                    @php
                                        $compares_num ++;
                                    @endphp
                                    @if($pricecompare->user_id == auth()->user()->id && $pricecompare->price_id == $price->id && $pricecompare->competitor_id == $pricecompetitor->id)
                                        @php
                                            $num_var ++;
                                            $compares_num --;
                                        @endphp
                                        <td align="center">
                                            {{ $pricecompare->description }}
                                        </td>
                                        <td align="center">
                                            {{ number_format($pricecompare->price, 2) ?? '' }} €
                                        </td>
                                    @endif
                                @endforeach
                                @if($compares_num == $pricecompares->count())
                                    <td align="center"></td>
                                    <td align="center"></td>
                                @endif
                            @endforeach
                            <td align="center">
                                <button type="button" style="width: 75px;" onclick="onesaveitem('{{$price->id}}')" class="btn-sm btn-green" data-toggle="modal" data-target="#myModal">
                                    Add Item
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Print PDF Modal Begin -->
    <div class="modal" id="printpdfmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.pricecompares.exportpdf') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Choose Competitor</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="roles" class="text-xs required">Competitors</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn-sm btn-indigo select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn-sm btn-indigo deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="select2{{ $errors->has('pricecompetitors') ? ' is-invalid' : '' }}" name="pricecompetitors[]" id="pricecompetitors" multiple required>
                                @foreach($pricecompetitors as $id => $pricecompetitor)
                                    <option value="{{ $pricecompetitor->id }}" {{ (in_array($id, old('pricecompetitors', []))) ? 'selected' : '' }}>{{ $pricecompetitor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Print</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Print PDF End Modal -->

    <!-- Modal Begin -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.pricecompares.onestore') }}" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Add Competitor</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div>
                            <input type="hidden" name="id" id="priceid" />
                        </div>

                        <div class="mb-3">
                            <label for="description" class="text-xs required">{{ trans('cruds.price.fields.desc') }}</label>

                            <div class="form-group">
                                <input type="text" id="description" name="description" class="{{ $errors->has('description') ? ' is-invalid' : '' }}" value="{{ old('description') }}" required>
                            </div>
                            @if($errors->has('description'))
                                <p class="invalid-feedback">{{ $errors->first('description') }}</p>
                            @endif
                            <span class="block"></span>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="text-xs required">{{ trans('cruds.price.fields.price') }}</label>

                            <div class="form-group">
                                <input type="text" id="price" name="price" class="{{ $errors->has('price') ? ' is-invalid' : '' }}" value="{{ old('price') }}" required>
                            </div>
                            @if($errors->has('price'))
                                <p class="invalid-feedback">{{ $errors->first('price') }}</p>
                            @endif
                            <span class="block"></span>
                        </div>

                        <div class="mb-3">
                            <label for="price_id" class="text-xs required">COMPETITOR</label>

                            <div class="form-group">
                                <select name="competitor_id" class="{{ $errors->has('competitor_id') ? ' is-invalid' : '' }}" required>
                                    <option value="">Choose Item</option>
                                    @foreach($pricecompetitors as $key => $pricecompetitor)
                                        <option value="{{ $pricecompetitor->id }}">
                                            {{ $pricecompetitor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('competitor_id'))
                                <p class="invalid-feedback">{{ $errors->first('competitor_id') }}</p>
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
        let table = $('.datatable-Pricecompare:not(.ajaxTable)').DataTable();
    });

    function onesaveitem(id) {
        document.getElementById('priceid').value = id;
    }
</script>
@endsection