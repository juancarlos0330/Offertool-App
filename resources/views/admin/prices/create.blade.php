@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.create') }} {{ trans('cruds.price.title_singular') }}
    </div>

    <form method="POST" action="{{ route('admin.scooters.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="body">
            
            <div class="mb-3">
                <label for="itemcode" class="text-xs required">{{ trans('cruds.price.fields.itemcode') }}</label>

                <div class="form-group">
                    <input type="text" id="itemcode" name="itemcode" class="{{ $errors->has('itemcode') ? ' is-invalid' : '' }}" value="{{ old('itemcode') }}" required>
                </div>
                @if($errors->has('itemcode'))
                    <p class="invalid-feedback">{{ $errors->first('itemcode') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.itemcode_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="description" class="text-xs required">{{ trans('cruds.price.fields.desc') }}</label>

                <div class="form-group">
                    <input type="text" id="description" name="description" class="{{ $errors->has('description') ? ' is-invalid' : '' }}" value="{{ old('description') }}" required>
                </div>
                @if($errors->has('description'))
                    <p class="invalid-feedback">{{ $errors->first('description') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.desc_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="price" class="text-xs required">{{ trans('cruds.price.fields.price') }}</label>

                <div class="form-group">
                    <input type="text" id="price" name="price" class="{{ $errors->has('price') ? ' is-invalid' : '' }}" value="{{ old('price') }}" required>
                </div>
                @if($errors->has('price'))
                    <p class="invalid-feedback">{{ $errors->first('price') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.price_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="pricetype_id" class="text-xs required">{{ trans('cruds.price.fields.typename') }}</label>

                <div class="form-group">
                    <select name="pricetype_id" name="pricetype_id" class="{{ $errors->has('pricetype_id') ? ' is-invalid' : '' }}" required>
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
                <span class="block">{{ trans('cruds.price.fields.typename_helper') }}</span>
            </div>
        </div>

        <div class="footer">
            <button type="submit" class="submit-button">{{ trans('global.save') }}</button>
        </div>
    </form>
</div>
@endsection
