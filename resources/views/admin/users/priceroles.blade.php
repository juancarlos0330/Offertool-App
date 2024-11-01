@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }} Price Role
    </div>

    <form method="POST" action="{{ route('admin.users-priceroleupdate', [$seluserid]) }}" enctype="multipart/form-data">
        @csrf
        <div class="body">
            <div class="mb-3">
                <label for="priceroles" class="text-xs required">{{ trans('cruds.user.fields.roles') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn-sm btn-indigo select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn-sm btn-indigo deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="select2{{ $errors->has('priceroles') ? ' is-invalid' : '' }}" name="priceroles[]" id="priceroles" multiple>
                    @foreach($pricetypes as $type)
                        <option value="{{ $type->id }}" {{ (in_array($type->id, old('pricetypes', []))) ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('priceroles'))
                    <p class="invalid-feedback">{{ $errors->first('priceroles') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div>
        </div>

        <div class="footer">
            <button type="submit" class="submit-button">{{ trans('global.save') }}</button>
        </div>
    </form>
</div>
@endsection